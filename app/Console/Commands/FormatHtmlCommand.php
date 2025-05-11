<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class FormatHtmlCommand extends Command
{
    protected $signature = 'html:format {inputPath}';
    protected $description = '使用 BeautifulSoup 將指定資料夾內所有 HTML 樣板格式化';

    public function handle()
    {
        $inputPath = base_path($this->argument('inputPath'));

        if (!preg_match('/\/originals$/', $inputPath)) {
            $this->error('來源路徑必須包含 originals');
            return Command::FAILURE;
        }

        if (!is_dir($inputPath)) {
            $this->error('來源路徑必須是資料夾');
            return Command::FAILURE;
        }

        $venvPython = base_path('scripts/venv/bin/python');
        if (!file_exists($venvPython)) {
            $this->error('找不到虛擬環境 Python，請先執行 make python-setup');
            return Command::FAILURE;
        }

        $sourceFiles = File::glob($inputPath . '/*.php');
        foreach ($sourceFiles as $sourcePath) {
            $destPath = str_replace('originals', 'formatted', $sourcePath);
            File::ensureDirectoryExists(dirname($destPath));

            $process = new Process([
                $venvPython,
                base_path('scripts/format_bs4.py'),
                $sourcePath
            ]);

            $process->run();

            if (!$process->isSuccessful()) {
                $this->error("❌ 格式化失敗：{$sourcePath}");
                throw new \RuntimeException($process->getErrorOutput());
            }

            File::put($destPath, $process->getOutput());
            $this->info("✅ 格式化完成：{$destPath}");
        }

        return Command::SUCCESS;
    }
}

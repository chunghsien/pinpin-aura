<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishThemeAssets extends Command
{
    /**
     * 指令定義與選項
     *
     * @var string
     */
    protected $signature = 'theme:publish-assets {--theme= : 要發佈資源的樣板名稱（例如：lezada）}';

    /**
     * The console command description.
     * 中文說明
     * @var string
     */
    protected $description = '將樣板資源檔案從 packages 複製到 public/themes 資料夾';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $theme = $this->option('theme') ?? 'lezada';

        $source = base_path("packages/pinpin/themes-{$theme}/public/assets");
        $destination = public_path("themes/{$theme}");

        if (!File::exists($source)) {
            $this->error("資源來源不存在：{$source}");
            return 1;
        }

        File::ensureDirectoryExists($destination);

        File::copyDirectory($source, $destination);

        $this->info("✅ 成功將樣板資源發佈至：public/themes/{$theme}");

        // --- 建立 Symbolic Link ---
        $targetTsPath = base_path("packages/pinpin/themes-{$theme}/resources/ts");
        $linkPath = base_path("resources/ts/themes/{$theme}");

        if (!File::exists($targetTsPath)) {
            $this->warn("⚠️ 找不到 TypeScript 資料夾：{$targetTsPath}，跳過建立 Symbolic Link。");
        } else {
            // 確保上層資料夾存在
            File::ensureDirectoryExists(dirname($linkPath));

            if (file_exists($linkPath) || is_link($linkPath)) {
                File::delete($linkPath);
            }

            symlink($targetTsPath, $linkPath);

            $this->info("✅ 成功建立 Symbolic Link：resources/ts/themes/{$theme}");
        }
        return 0;
    }
}

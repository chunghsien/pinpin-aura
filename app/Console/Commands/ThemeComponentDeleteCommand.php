<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ThemeComponentDeleteCommand extends Command
{
    protected $signature = '
        theme:livewire:delete
        {name : 元件名稱（如 headers/base）}
        {--org= : package組織名稱(預設為 pinpin )}
        {--theme= : 主題名稱（如 themes-lezada ）}
        {--force : 跳過確認直接刪除}
    ';

    protected $description = '刪除指定主題套件中的 Blade 元件與相關註冊';

    public function handle()
    {
        $name = $this->argument('name');
        $org = $this->option('org') ?? 'pinpin';
        $theme = $this->option('theme') ?? 'themes-lezada';
        $force = $this->option('force');

        $pathParts = explode('/', $name);
        $className = Str::studly(array_pop($pathParts));
        $relativeDir = implode('/', array_map([Str::class, 'studly'], $pathParts));
        $bladeDir = implode('/', array_map([Str::class, 'kebab'], $pathParts));
        $fileName = Str::kebab($className);

        $basePath = base_path("packages/{$org}/{$theme}");
        $classPath = $basePath . '/src/Http/Livewire' . ($relativeDir ? "/{$relativeDir}" : '') . "/{$className}.php";
        $viewPath = $basePath . "/resources/views/livewire" . ($bladeDir ? "/{$bladeDir}" : '') . "/{$fileName}.blade.php";
        $testPath = $basePath . "/tests/Feature/Http/{$className}Test.php";

        if (! $force && ! $this->confirm("你確定要刪除 Livewire 元件 {$className} 嗎？")) {
            $this->warn("已取消刪除。");

            return 0;
        }

        foreach ([$classPath, $viewPath, $testPath] as $path) {
            if (File::exists($path)) {
                File::delete($path);
                $this->info("已刪除：{$path}");
            }
        }

        $this->info("Blade 元件 {$className} 已移除完成。");

        return 0;
    }
}

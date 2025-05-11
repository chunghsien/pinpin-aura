<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ThemeComponentDeleteCommand extends Command
{
    protected $signature = '
        theme:blade-component:delete
        {name : 元件名稱（如 Admin/HeaderMenu）}
        {--org= : package組織名稱(預設為pinpin)}
        {--theme= : 主題名稱（如 lezada）}
        {--force : 跳過確認直接刪除}
    ';

    protected $description = '刪除指定主題套件中的 Blade 元件與相關註冊';

    public function handle()
    {
        $name = $this->argument('name');
        $org = $this->option('org') ?? 'pinpin';
        $theme = $this->option('theme') ?? 'lezada';
        $force = $this->option('force');

        $pathParts = explode('/', $name);
        $className = Str::studly(array_pop($pathParts));
        $relativeDir = implode('/', array_map([Str::class, 'studly'], $pathParts));
        $bladeDir = implode('/', array_map([Str::class, 'kebab'], $pathParts));
        $fileName = Str::kebab($className);

        $basePath = base_path("packages/{$org}/themes-{$theme}");
        $classPath = $basePath . '/src/View/Components' . ($relativeDir ? "/{$relativeDir}" : '') . "/{$className}.php";
        $viewPath = $basePath . "/resources/views/components" . ($bladeDir ? "/{$bladeDir}" : '') . "/{$fileName}.blade.php";
        $testPath = $basePath . "/tests/Feature/View/{$className}Test.php";
        $providerPath = "$basePath/src/Themes{$theme}/Themes{$theme}ServiceProvider.php";  // Blade Component Provider
        $composerPath = "$basePath/composer.json";

        if (!$force && !$this->confirm("你確定要刪除 Blade 元件 {$className} 嗎？")) {
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

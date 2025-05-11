<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InstalledTheme;
use App\Models\ThemeComponent;
use Illuminate\Support\Str;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class InstallThemeCommand extends Command
{
    protected $signature = '
        theme:install
        {--package= : package最頂端名稱}
        {--name= : 主題名稱}
        {--force : 強制更新已安裝主題}
    ';

    protected $description = '將「樣式」主題安裝到系統中。';

    public function handle()
    {
        $package = $this->option('package');
        if (!$package) {
            $this->error('你必須指定 --package');
            return 1;
        }

        $slug = $this->option('name');
        if (!$slug) {
            $this->error('你必須指定 --name');
            return 1;
        }

        $themePackageFolder = base_path("packages/{$package}/themes-{$slug}");
        if (!is_dir($themePackageFolder)) {
            $this->error("{$themePackageFolder} 不存在");
            return 1;
        }

        $force = $this->option('force');
        $exists = InstalledTheme::where('slug', $slug)->exists();

        if ($exists && !$force) {
            $this->warn("主題 [$slug] 已經安裝過了。");
            $action = $this->choice(
                '請選擇接下來的動作：',
                ['停止執行', '更新主題資料'],
                0
            );

            if ($action === '停止執行') {
                $this->info("已取消安裝流程。");
                return 0;
            }
        }

        $installOptionLoadPath = "{$themePackageFolder}/config/{$slug}.php";
        $installOption = app('array-loader')::load($installOptionLoadPath);

        $installedTheme = InstalledTheme::updateOrCreate(
            ['slug' => $slug],
            $installOption
        );

        // 🆕 掃描 Blade Component 路徑並自動註冊
        $this->registerBladeComponents($installedTheme, "{$themePackageFolder}/resources/views/components");

        $installedTheme->activate();
        $this->info("主題 [$slug] 已成功" . ($exists ? '更新' : '安裝') . "！");
    }

    /**
     * 掃描 Blade Component 並寫入 ThemeComponent 資料表
     */
    protected function registerBladeComponents($installedTheme, $componentPath)
    {
        if (!is_dir($componentPath)) {
            $this->warn("未找到 Blade Component 目錄：{$componentPath}");
            return;
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($componentPath));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'blade.php') {
                $fullPath = $file->getPathname();
                $relativePath = str_replace($componentPath . DIRECTORY_SEPARATOR, '', $fullPath);
                $componentName = Str::of($relativePath)
                    ->replace(DIRECTORY_SEPARATOR, '.')
                    ->replace('.blade.php', '')
                    ->toString();

                $where = [
                    'installed_theme_id' => $installedTheme->id,
                    'component_type' => 'blade',
                    'resolve_name' => $componentName,
                ];
                $data = [
                    'installed_theme_id' => $installedTheme->id,
                    'component_type' => 'blade',
                    'resolve_name' => $componentName,
                ];

                ThemeComponent::updateOrCreate($where, $data);
            }
        }

        $this->info("Blade Components 註冊完成。");
    }
}

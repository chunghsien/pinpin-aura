<?php

namespace App\Console\Commands;

use App\Models\HeaderFooterStyle;
use Illuminate\Console\Command;
use App\Models\InstalledTheme;
use App\Models\SiteStyle;
use App\Models\ThemeComponent;
use Illuminate\Support\Str;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class InstallThemeCommand extends Command
{
    //package組織名稱(預設為pinpin
    protected $signature = '
        theme:install
        {--org= : package組織名稱(ex.pinpin}
        {--name= : 主題名稱ex.themes-lezada, core-ui-admin}
        {--force : 強制更新已安裝主題}
    ';

    protected $description = '將「樣式」主題安裝到系統中。';

    protected function createSymlink($source, $target)
    {
        if (is_link($target)) {
            $this->warn("Symlink 已存在：$target");
            return;
        }

        if (file_exists($target)) {
            $this->warn("目標已存在且非 Symlink：$target");
            return;
        }

        if (!file_exists($source)) {
            $this->warn("來源資料夾不存在：$source");
            return;
        }

        // 確保目標目錄存在
        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0755, true);
        }

        symlink($source, $target);
        $this->info("已建立 Symlink：$target -> $source");
    }


    public function handle()
    {
        $org = $this->option('org');
        if (! $org) {
            $this->error('你必須指定 --org');
            return 1;
        }

        $slug = $this->option('name');
        if (!$slug) {
            $this->error('你必須指定 --name');
            return 1;
        }

        $themePackageFolder = base_path("packages/{$org}/{$slug}");
        if (!is_dir($themePackageFolder)) {
            $this->error("該主題名稱 {$slug} 不存在");
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
        $this->registerLivewireComponents($installedTheme, "{$themePackageFolder}/resources/views/livewire");
        $installedTheme->activate($installedTheme->use_type);
        $tsSource = base_path("packages/{$org}/{$slug}/resources/ts");
        $tsTarget = base_path("resources/ts/themes/{$slug}");
        $this->createSymlink($tsSource, $tsTarget);
        $headerFooterStyleSeederClass = Str::studly($org) . '\\' . Str::studly($slug) . '\\Database\\Seeders\\HeaderFooterStyleSeeder';

        if (class_exists($headerFooterStyleSeederClass)) {
            $headerFooterStyleSeederReflection = new \ReflectionClass($headerFooterStyleSeederClass);
            $headerFooterStyleSeederReflection->newInstance()->run($installedTheme->id);
        }
        if (SiteStyle::where('use_type', '=', 'web')->count() == 0) {
            SiteStyle::insert([
                'use_type' => 'web'
            ]);
        }
        $headerStyle = HeaderFooterStyle::where('theme_id', '=', $installedTheme->id)
            ->where('type', '=', 'header')->where('is_active', '=', 1)->first();
        if ($headerStyle) {
            SiteStyle
                ::where('use_type', '=', 'web')
                ->update(['header_style_id' => $headerStyle->id]);
        }
        //
        if (SiteStyle::where('use_type', '=', 'web-mobile')->count() == 0) {
            SiteStyle::insert([
                'use_type' => 'web-mobile'
            ]);
        }
        $mobileHeaderStyle = HeaderFooterStyle
            ::where('theme_id', '=', $installedTheme->id)
            ->whereLike('slug', '%mobile%')
            ->where('type', '=', 'header')->where('is_active', '=', 1)->first() ?? $headerStyle;
        if ($mobileHeaderStyle) {
            SiteStyle
                ::where('use_type', '=', 'web-mobile')
                ->update(['header_style_id' => $mobileHeaderStyle->id]);
        }
        $this->info("主題 [$slug] 已成功" . ($exists ? '更新' : '安裝') . "！");
    }

    /**
     * 掃描 Livewire Component 並寫入 ThemeComponent 資料表
     */
    protected function registerLivewireComponents($installedTheme, $componentPath)
    {
        if (!is_dir($componentPath)) {
            $this->warn("未找到 Livewire Component 目錄：{$componentPath}");
            return;
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($componentPath));
        foreach ($iterator as $file) {
            $fullPath = $file->getPathname();
            if ($file->isFile() && preg_match('/\.blade\.php$/', $fullPath)) {
                $relativePath = str_replace($componentPath . DIRECTORY_SEPARATOR, '', $fullPath);
                $componentName = Str::of($relativePath)
                    ->replace(DIRECTORY_SEPARATOR, '.')
                    ->replace('.blade.php', '')
                    ->toString();

                $where = [
                    'installed_theme_id' => $installedTheme->id,
                    'component_type' => 'livewire',
                    'resolve_name' => $componentName,
                ];
                $data = [
                    'installed_theme_id' => $installedTheme->id,
                    'component_type' => 'livewire',
                    'resolve_name' => $componentName,
                ];

                ThemeComponent::updateOrCreate($where, $data);
            }
        }

        $this->info("Blade Components 註冊完成。");
    }
}

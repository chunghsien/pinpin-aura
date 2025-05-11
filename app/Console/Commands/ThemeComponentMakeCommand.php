<?php

namespace App\Console\Commands;

use App\Support\PackageClassMapperManger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ThemeComponentMakeCommand extends Command
{
    protected $signature = '
        theme:blade-component
        {name : 元件名稱（如 HeaderWrapper）}
        {--org= : package 組織（預設 pinpin）}
        {--theme= : 主題名稱（預設 lezada）}
    ';

    protected $description = '在指定的主題套件中建立 Blade Component';

    public function handle()
    {
        $name = $this->argument('name');
        $org = $this->option('org') ?? 'pinpin';
        $theme = $this->option('theme') ?? 'lezada';
        $orgStudly = Str::studly($org);
        $themeStudly = Str::studly($theme);

        // 路徑與命名轉換
        $pathParts = explode('/', $name);
        $className = Str::studly(array_pop($pathParts));
        $namespacePath = implode('\\', array_map([Str::class, 'studly'], $pathParts));
        $relativeDir = implode('/', array_map([Str::class, 'studly'], $pathParts));
        $bladeDir = implode('/', array_map([Str::class, 'kebab'], $pathParts));
        $fileName = Str::kebab($className);

        $basePath = base_path("packages/{$org}/themes-{$theme}");
        $classPath = $basePath . '/src/View/Components' . ($relativeDir ? "/{$relativeDir}" : '') . "/{$className}.php";
        $viewPath = $basePath . "/resources/views/components" . ($bladeDir ? "/{$bladeDir}" : '') . "/{$fileName}.blade.php";

        $namespace = "{$orgStudly}\\Themes{$themeStudly}\\View\\Components" . ($namespacePath ? "\\{$namespacePath}" : '');

        // stub 路徑
        $stubClass = base_path('stubs/blade-class.stub');
        $stubView = base_path('stubs/blade-view.stub');

        if (!File::exists($stubClass) || !File::exists($stubView)) {
            $this->error('請確認 stubs/blade-class.stub 與 blade-view.stub 是否存在於 stubs 資料夾');
            return 1;
        }

        // 生成 Class 檔案內容
        if (!File::isFile($classPath)) {
            $classTemplate = File::get($stubClass);
            $classContent = str_replace(
                ['{{ namespace }}', '{{ class }}', '{{ view }}'],
                [$namespace, $className, "themes-{$theme}::components" . ($bladeDir ? ".{$bladeDir}" : '') . ".{$fileName}"],
                $classTemplate
            );

            File::ensureDirectoryExists(dirname($classPath));
            File::put($classPath, $classContent);
        } else {
            $this->warn("$classPath 已存在");
        }

        // 生成 Blade 檔案內容
        if (!File::isFile($viewPath)) {
            $viewContent = str_replace('{{ class }}', $className, File::get($stubView));
            File::ensureDirectoryExists(dirname($viewPath));
            File::put($viewPath, $viewContent);
        } else {
            $this->warn("Blade $viewPath 已存在");
        }

        $this->info("Blade Component {$className} 建立完成！");
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Support\PackageClassMapperManger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ThemeComponentMakeCommand extends Command
{
    protected $signature = '
        theme:livewire
        {name : 分類名稱/元件名稱（如 headers/base）}
        {--org= : package 組織（預設 pinpin）}
        {--theme= : 主題名稱（預設 themes-lezada）}
        {--test= : 同時建立元件的測試檔案}
    ';

    protected $description = '在指定的主題套件中建立 Livewire Component';

    public function handle()
    {
        $name = $this->argument('name');
        $org = $this->option('org') ?? 'pinpin';
        $theme = $this->option('theme') ?? 'themes-lezada';
        $withTest = $this->option('test');
        $orgStudly = Str::studly($org);
        $themeStudly = Str::studly($theme);

        // 路徑與命名轉換
        $pathParts = explode('/', $name);
        $className = Str::studly(array_pop($pathParts));

        $namespacePath = implode('\\', array_map([Str::class, 'studly'], $pathParts));

        $relativeDir = implode('/', array_map([Str::class, 'studly'], $pathParts));
        $livewireDir = implode('/', array_map([Str::class, 'kebab'], $pathParts));
        $fileName = Str::kebab($className);
        $basePath = base_path("packages/{$org}/{$theme}");
        $classPath = $basePath . '/src/Http/Livewire' . ($relativeDir ? "/{$relativeDir}" : '') . "/{$className}.php";
        $viewPath = $basePath . "/resources/views/livewire" . ($livewireDir ? "/{$livewireDir}" : '') . "/{$fileName}.blade.php";
        $testPath = "$basePath/tests/Feature/Http/Livewire/{$className}Test.php";
        $namespace = "{$orgStudly}\\{$themeStudly}\\Http\\Livewire" . ($namespacePath ? "\\{$namespacePath}" : '');

        // stub 路徑
        $stubClass = base_path('stubs/livewire.stub');
        $stubView = base_path('stubs/livewire.view.stub');
        $stubTest = base_path('stubs/livewire.test.stub');

        if (!File::exists($stubClass) || !File::exists($stubView)) {
            $this->error('請確認 stubs/livewire.stub 與 livewire.view.stub 是否存在於 stubs 資料夾');
            return 1;
        }

        // 生成 Class 檔案內容
        if (!File::isFile($classPath)) {
            $classTemplate = File::get($stubClass);
            $middlePath = str_replace('/', '.', ($livewireDir ? ".{$livewireDir}" : ''));
            $classContent = str_replace(
                ['[namespace]', '[class]', '[view]'],
                [
                    $namespace,
                    $className,
                    "{$theme}::livewire" . $middlePath . ".{$fileName}"
                ],
                $classTemplate
            );
            File::ensureDirectoryExists(dirname($classPath));
            File::put($classPath, $classContent);
        } else {
            $this->warn("$classPath 已存在");
        }

        // 生成 livewire 檔案內容
        if (!File::isFile($viewPath)) {
            $viewContent = str_replace(
                ['[class]', '[quote]'],
                [
                    $className,
                    "{$theme}::livewire" . ($livewireDir ? ".{$livewireDir}" : '') . ".{$fileName}"
                ],
                File::get($stubView)
            );
            File::ensureDirectoryExists(dirname($viewPath));
            File::put($viewPath, $viewContent);
        } else {
            $this->warn("livewire $viewPath 已存在");
        }
        // 建立測試檔案（若指定）
        if ($withTest) {
            if (!File::exists($stubTest)) {
                $this->warn('未找到 stubs/livewire.test.stub，略過測試檔案建立');
            } else {
                $testClass = $className . "Test";
                $classWithNamespace = $namespace . '\\' . $className;
                $testContent = str_replace(
                    [
                        '[testnamespace]',
                        '[classwithnamespace]',
                        '[testclass]',
                        '[class]'
                    ],
                    [
                        "Pinpin\\ThemesLezada\\Tests\\Feature\\Http\\Livewire",
                        $classWithNamespace,
                        $testClass,
                        $className
                    ],
                    File::get($stubTest)
                );
                File::ensureDirectoryExists(dirname($testPath));
                File::put($testPath, $testContent);
                $this->info("Livewire 測試檔 {$className}Test 建立完成！");
            }
        }

        $this->info("livewire Component {$className} 建立完成！");
        return 0;
    }
}

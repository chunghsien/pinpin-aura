<?php

declare(strict_types=1);

namespace App\Providers;

use App\Helpers\ViteHelper;
use App\Support\ArrayFileLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $repositoriesPath = app_path('Repositories');
        $respositoryNamespace = 'App\Repositories';
        foreach (File::allFiles($repositoriesPath) as $file) {
            $relativePath = str_replace(
                [$repositoriesPath . '/', '.php'],
                '',
                $file->getRealPath()
            );
            $relativeClassPath = str_replace('/', '\\', $relativePath);
            $interface = $respositoryNamespace . '\\' . $relativeClassPath;
            if (preg_match('/Interface$/', $interface)) {
                $repositoryFullClass = preg_replace('/Interface$/', '', $interface);
                $repositoryFullClass = preg_replace('/\\\\Contracts/', '', $repositoryFullClass);
                if (class_exists($repositoryFullClass) && interface_exists($interface)) {
                    $this->app->bind($interface, $repositoryFullClass);
                    $this->app->singleton($interface, $repositoryFullClass);
                }

                continue;
            }
        }

        $this->app->singleton('array-loader', function () {
            return new ArrayFileLoader();
        });

        $servicesPath = app_path('Services');
        $serviceNamespace = 'App\Services';
        foreach (File::allFiles($servicesPath) as $file) {
            $relativePath = str_replace([$servicesPath . '/', '.php'], '', $file->getRealPath());
            $relativeClassPath = str_replace('/', '\\', $relativePath);
            $fullClass = $serviceNamespace . '\\' . $relativeClassPath;
            if (class_exists($fullClass)) {
                $reflection = new ReflectionClass($fullClass);
                $parameters = [];
                foreach ($reflection->getConstructor()->getParameters() as $parameterInstance) {
                    $parameters[] = $this->app->make($parameterInstance->getType()->getName());
                }
                $this->app->singleton($fullClass, function (/*$app*/) use ($reflection, $parameters) {
                    return $reflection->newInstance(...$parameters);
                });
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $className = ViteHelper::class;
        Blade::directive('viteAsset', fn ($expression) => "<?php echo {$className}::scriptTag($expression); ?>");

        Blade::directive('viteAssetByRoute', fn ($themeName) => "<?php echo {$className}::scriptTagByRoute($themeName); ?>");
    }
}

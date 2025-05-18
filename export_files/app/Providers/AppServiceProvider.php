<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Repositories\SettingRepository;
use App\Repositories\SettingRepositoryInterface;
use App\Support\ArrayFileLoader;
use App\Helpers\ViteHelper;
use App\Repositories\ThemeRepository;
use App\Repositories\ThemeRepositoryInterface;
use App\Services\ThemeService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->singleton('array-loader', function () {
            return new ArrayFileLoader();
        });
        $this->app->singleton(ThemeRepositoryInterface::class, ThemeRepository::class);
        $this->app->singleton(ThemeService::class, function ($app) {
            return new ThemeService($app->make(ThemeRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $className = ViteHelper::class;
        Blade::directive('viteAsset', fn($expression) => "<?php echo {$className}::scriptTag($expression); ?>");

        Blade::directive('viteAssetByRoute', fn($themeName) => "<?php echo {$className}::scriptTagByRoute($themeName); ?>");
    }
}

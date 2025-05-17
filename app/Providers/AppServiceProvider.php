<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\SettingRepository;
use App\Repositories\SettingRepositoryInterface;
use App\Support\ArrayFileLoader;
use Illuminate\Support\Facades\Blade;
use App\Helpers\ViteHelper;

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

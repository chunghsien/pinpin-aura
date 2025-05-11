<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\SettingRepository;
use App\Repositories\SettingRepositoryInterface;
use App\Support\ArrayFileLoader;

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
        //
    }
}

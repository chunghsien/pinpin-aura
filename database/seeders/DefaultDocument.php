<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class DefaultDocument extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = collect(Route::getRoutes())
            ->filter(function ($route) {
                return $route->getName() && $route->getName() != 'storage.local'; // 保留有命名的 Routes
            })
            ->map(function ($route) {
                $methods = array_values(array_filter($route->methods(), function ($method) {
                    return strtoupper($method) !== 'HEAD';
                }));
                return [
                    'index' => $route->getName(),
                    'name' => $route->getName(),
                    'theme_id' => 0,
                    'sort' => 16777215,
                    'is_allowed_methods' => json_encode($methods),
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->values();

        if ($routes->isNotEmpty()) {
            DB::table('documents')->upsert(
                $routes->toArray(),
                ['index'], // 唯一鍵條件
                ['name', 'is_allowed_methods'] // 更新的欄位
            );
        }
    }
}

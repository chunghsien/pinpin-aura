<?php

// app/Helpers/ViteHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class ViteHelper
{
    protected static $manifest;

    protected static function loadManifest()
    {
        if (is_null(self::$manifest)) {
            $manifestPath = public_path('output-js/manifest.json');
            if (file_exists($manifestPath)) {
                self::$manifest = json_decode(file_get_contents($manifestPath), true);
            } else {
                //dd(file_get_contents(public_path('build/manifest.json')));
                self::$manifest = [];
            }
        }
    }

    protected static function isDev(): bool
    {
        return App::environment('local'); // 可以依需要改成讀取 .env
    }

    public static function scriptTag(string $entry): string
    {
        if (self::isDev()) {
            // 開發環境走 Vite Dev Server，直接載入原始 TS 檔案
            return '<script type="module" src="http://localhost:5173/' . $entry . '"></script>';
        }

        self::loadManifest();
        $file = self::$manifest[$entry]['file'] ?? null;

        return $file
            ? '<script type="module" src="' . asset('output-js/' . $file) . '"></script>'
            : '';
    }

    public static function scriptTagByRoute(): string
    {
        $routeName = Route::currentRouteName();
        if (!$routeName) return '';

        $entry = "resources/ts/pages/{$routeName}.ts";
        return self::scriptTag($entry);
    }
}

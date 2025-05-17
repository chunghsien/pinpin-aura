<?php

// app/Helpers/ViteHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Vite;

class ViteHelper
{
    protected static $manifest;

    protected static function loadManifest()
    {
        if (is_null(self::$manifest)) {
            $manifestPath = str_replace('public/', '', env('TS_OUT_DIR') . '/.vite/manifest.json');
            $manifestPath = public_path($manifestPath);
            if (file_exists($manifestPath)) {
                $tmp = json_decode(file_get_contents($manifestPath)/*, true*/);
                foreach ($tmp as $fileObj) {
                    $fileObj->file = env('TS_OUT_DIR') . '/' . $fileObj->file;
                    $fileObj->file = str_replace('public', '', $fileObj->file);
                }
                self::$manifest = $tmp;
            } else {

                self::$manifest = [];
            }
        }
    }

    protected static function isDev(): bool
    {
        // 可以依需要改成讀取 .env
        return env('APP_ENV') === 'local';
    }

    public static function scriptTag(string $entry): string
    {

        if (self::isDev()) {
            $hotDomainProtocol = File::get(Vite::hotFile());
            // 開發環境走 Vite Dev Server，直接載入原始 TS 檔案
            return "<script type=\"module\" src=\"{$hotDomainProtocol}/{$entry}\"></script>";
        }
        self::loadManifest();
        $file = self::$manifest->{$entry}->file ?? null;
        return File::isFile(public_path($file))
            ? "<script type=\"module\" src=\"{$file}\"></script>"
            : '';
    }

    public static function scriptTagByRoute(string $themeName): string
    {

        $routeName = Route::currentRouteName();
        if (!$routeName) return '';
        $entry = "resources/ts/themes/{$themeName}/pages/{$routeName}.ts";
        return self::scriptTag($entry);
    }
}

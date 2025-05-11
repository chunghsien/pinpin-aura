<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use InvalidArgumentException;

class PackageOptionLoader
{
    protected string $packagePath;

    protected string $package;

    protected string $org;

    public function __construct(string $org, string $package)
    {
        $this->packagePath = base_path("packages/{$org}/{$package}/config/class_mapper");
        $this->package = $package;
        $this->org = $org;
        if (!is_dir($this->packagePath)) {
            throw new InvalidArgumentException("無法找到 class_mapper 路徑: {$this->packagePath}");
        }
    }

    public function load(string $filename): array
    {
        $filePath = "{$this->packagePath}/{$filename}.php";

        if (!File::exists($filePath)) {
            if (app()->runningInConsole()) {
                return [];
            } else {
                throw new \Exception("請使用php artisan package:option-refresh -h 查看使用方式後刷新設定檔", 1);
            }
        }
        if (File::exists($filePath)) {

            $config = app('array-loader')::load($filePath);

            if (!is_array($config)) {
                throw new InvalidArgumentException("設定檔需回傳陣列: {$filePath}");
            }
            return $config;
        }
        return [];
    }

    public function loadAll(): array
    {
        $all = [];
        foreach (File::files($this->packagePath) as $file) {
            if ($file->getExtension() === 'php') {
                $name = $file->getFilenameWithoutExtension();
                $all[$name] = app('array-loader')::load($file->getRealPath());
            }
        }
        return $all;
    }
}

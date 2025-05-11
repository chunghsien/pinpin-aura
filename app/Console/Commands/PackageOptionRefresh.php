<?php
// app/Console/Commands/PackageOptionRefresh.php

namespace App\Console\Commands;

use App\Support\PackageClassMapperManger;
use Illuminate\Console\Command;

class PackageOptionRefresh extends Command
{
    protected $signature = '
        package:option-refresh
        {--org= : 指定 package org (預設 pinpin)}
        {--package= : 指定 package name (預設 themes-lezada)}
    ';

    protected $description = '重新整理並驗證 package 的 class_mappers 設定';

    public function handle()
    {
        $org = $this->option('org') ?? 'pinpin';
        $package = $this->option('package') ?? 'themes-lezada';
        //$outputType = 'php';

        $dir = base_path("packages/{$org}/{$package}/config/class_mapper");

        if (!is_dir($dir)) {
            $this->error("找不到 class_mappers 目錄: $dir");
            return 1;
        }
        $classMapperManger = new PackageClassMapperManger($org, $package);
        foreach (['livewire', 'command'] as $type) {
            $classMapperManger->scanAndSave($type);
        }

        $this->info("✅ 完成 Package Option Refresh");

        return 0;
    }
}

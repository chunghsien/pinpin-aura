<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageLocaleSeeder extends Seeder
{
    /**
     * 執行資料庫填充
     */
    public function run(): void
    {
        // 填充語言資料
        $initialSql = file_get_contents(__DIR__ . '/data/language.sql.stub');
        $initialSql = str_replace('`?`', sprintf('`%s`', 'languages'), $initialSql);
        DB::connection()->getPdo()->exec($initialSql);

        // 填充地區資料
        $initialSql = file_get_contents(__DIR__ . '/data/locale.sql.stub');
        $initialSql = str_replace('`?`', sprintf('`%s`', 'locales'), $initialSql);
        DB::connection()->getPdo()->exec($initialSql);

        // 填充語言地區關聯資料
        $initialSql = file_get_contents(__DIR__ . '/data/language_locale.sql.stub');
        $initialSql = str_replace('`?`', sprintf('`%s`', 'language_locale'), $initialSql);
        DB::connection()->getPdo()->exec($initialSql);
    }
}

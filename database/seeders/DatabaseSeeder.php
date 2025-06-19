<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Pinpin\ThemesLezada\Database\Seeders\HeaderFooterStyleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LanguageLocaleSeeder::class,
            DefaultDocument::class,
            CurrenciesTableSeeder::class,
            HeaderFooterStyleSeeder::class
        ]);
    }
}

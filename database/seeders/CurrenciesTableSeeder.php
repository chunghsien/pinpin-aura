<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $pathname = base_path('vendor/sokil/php-isocodes-db-only/databases/iso_4217.json');

        if (!is_file($pathname)) {
            $this->command->error("找不到貨幣 JSON 檔案，路徑：{$pathname}");
            return;
        }

        $data = json_decode(file_get_contents($pathname), true);
        if (empty($data['4217']) || !is_array($data['4217'])) {
            $this->command->error('JSON 檔案的資料結構不正確。');
            return;
        }

        $currencies = $data['4217'];
        $insertValues = [];

        foreach ($currencies as $c) {
            $code = strtoupper($c['alpha_3'] ?? '');
            if (empty($code)) {
                continue; // 跳過無效代碼
            }

            $name = $c['name'] ?? '';
            $isActive = (strtolower($code) === 'twd') ? 1 : 0;
            $fractionDigits = in_array(strtolower($code), ['twd', 'jpy', 'cny', 'kpw'], true) ? 0 : 2;

            $insertValues[] = [
                'code'         => $code,
                'name'         => $name,
                'is_active'    => $isActive,
                'fraction_digits' => $fractionDigits,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        if (empty($insertValues)) {
            $this->command->info('沒有可插入的貨幣資料。');
            return;
        }

        // 使用 upsert 確保 code 唯一，不重複插入
        collect($insertValues)
            ->chunk(500)
            ->each(function ($chunk) {
                DB::table('currencies')->upsert(
                    $chunk->toArray(),
                    ['code'], // 以 code 作為唯一鍵
                    ['name', 'is_active', 'fraction_digits', 'updated_at'] // 碰撞時更新的欄位
                );
            });

        $this->command->info('已成功匯入或更新 currencies 資料表。');
    }
}

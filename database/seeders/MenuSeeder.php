<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Helpers\MenuStructureHelper;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // 清除現有選單資料
        Menu::truncate();

        // 從靜態配置檔案轉換並插入資料庫
        $menuData = MenuStructureHelper::convertToMenusFormat();

        // 批次插入資料
        foreach ($menuData as $menu) {
            Menu::create($menu);
        }

        $this->command->info('Menu data seeded successfully!');
    }
}

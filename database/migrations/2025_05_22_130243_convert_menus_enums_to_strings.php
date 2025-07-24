<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        // 由於 MySQL 的 enum 修改限制，我們需要先備份資料，然後重建欄位

        // 1. 備份現有資料
        $existingMenus = DB::table('menus')->get();

        Schema::table('menus', function (Blueprint $table) {
            // 2. 刪除舊的 enum 欄位
            $table->dropColumn(['type', 'link_type']);
        });

        Schema::table('menus', function (Blueprint $table) {
            // 3. 重新建立為 string 欄位
            $table->string('type', 50)
                ->default('header')
                ->after('name')
                ->comment('選單位置類型');

            $table->string('link_type', 50)
                ->default('internal')
                ->after('parent_id')
                ->comment('連結類型');
        });

        // 4. 恢復資料
        foreach ($existingMenus as $menu) {
            DB::table('menus')
                ->where('id', $menu->id)
                ->update([
                    'type' => $menu->type,
                    'link_type' => $menu->link_type,
                ]);
        }
    }

    public function down(): void
    {
        // 備份現有資料
        $existingMenus = DB::table('menus')->get();

        Schema::table('menus', function (Blueprint $table) {
            // 刪除 string 欄位
            $table->dropColumn(['type', 'link_type']);
        });

        Schema::table('menus', function (Blueprint $table) {
            // 恢復為 enum 欄位
            $table->enum('type', ['header', 'footer', 'side', 'mobile', 'topbar'])
                ->default('header')
                ->after('name')
                ->comment('選單位置類型');

            $table->enum('link_type', ['internal', 'external'])
                ->default('internal')
                ->after('parent_id')
                ->comment('連結類型');
        });

        // 恢復資料
        foreach ($existingMenus as $menu) {
            DB::table('menus')
                ->where('id', $menu->id)
                ->update([
                    'type' => $menu->type,
                    'link_type' => $menu->link_type,
                ]);
        }
    }
};

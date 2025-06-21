<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // 選單樣式相關欄位
            $table->string('menu_style', 50)
                ->default('simple')
                ->after('icon')
                ->comment('選單樣式類型');

            $table->tinyInteger('mega_columns')
                ->nullable()
                ->after('menu_style')
                ->comment('Mega Menu 欄位數量 (4-5)');

            $table->string('column_title', 100)
                ->nullable()
                ->after('mega_columns')
                ->comment('Mega Menu 欄位標題');

            // 視覺元素相關欄位
            $table->string('image_url', 255)
                ->nullable()
                ->after('column_title')
                ->comment('選單項目圖片 URL');

            $table->string('menu_image_url', 255)
                ->nullable()
                ->after('image_url')
                ->comment('Mega Menu 右側裝飾圖片 URL');

            $table->boolean('has_image')
                ->default(false)
                ->after('menu_image_url')
                ->comment('是否包含圖片');

            // CSS 和樣式相關欄位
            $table->string('css_class', 255)
                ->nullable()
                ->after('has_image')
                ->comment('自定義 CSS 類別');

            $table->string('custom_attributes', 500)
                ->nullable()
                ->after('css_class')
                ->comment('自定義 HTML 屬性 (JSON 格式)');

            // 顯示控制欄位
            $table->json('display_rules')
                ->nullable()
                ->after('custom_attributes')
                ->comment('顯示規則 (裝置類型、用戶權限等)');

            $table->boolean('is_mega_column')
                ->default(false)
                ->after('display_rules')
                ->comment('是否為 Mega Menu 欄位項目');

            $table->boolean('is_column_title')
                ->default(false)
                ->after('is_mega_column')
                ->comment('是否為欄位標題 (不可點擊)');

            // 描述和 SEO 相關欄位
            $table->text('description')
                ->nullable()
                ->after('is_column_title')
                ->comment('選單項目描述');

            $table->string('tooltip', 255)
                ->nullable()
                ->after('description')
                ->comment('滑鼠懸停提示文字');

            // 進階功能欄位
            $table->boolean('is_featured')
                ->default(false)
                ->after('tooltip')
                ->comment('是否為特色選單項目');

            $table->string('badge_text', 50)
                ->nullable()
                ->after('is_featured')
                ->comment('徽章文字 (如 "New", "Hot")');

            $table->string('badge_color', 20)
                ->nullable()
                ->after('badge_text')
                ->comment('徽章顏色');

            // 索引優化
            $table->index(['type', 'is_active', 'order'], 'idx_menus_type_active_order');
            $table->index(['parent_id', 'order'], 'idx_menus_parent_order');
            $table->index(['menu_style'], 'idx_menus_style');
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // 移除索引
            $table->dropIndex('idx_menus_type_active_order');
            $table->dropIndex('idx_menus_parent_order');
            $table->dropIndex('idx_menus_style');

            // 移除欄位
            $table->dropColumn([
                'menu_style',
                'mega_columns',
                'column_title',
                'image_url',
                'menu_image_url',
                'has_image',
                'css_class',
                'custom_attributes',
                'display_rules',
                'is_mega_column',
                'is_column_title',
                'description',
                'tooltip',
                'is_featured',
                'badge_text',
                'badge_color'
            ]);
        });
    }
};

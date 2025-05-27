<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('選單項目名稱');
            $table->enum('type', ['header', 'footer', 'side', 'mobile', 'topbar'])->default('header')->comment('選單位置類型');
            $table->foreignId('parent_id')->nullable()->constrained('menus')->nullOnDelete()->comment('父選單 ID');
            $table->enum('link_type', ['internal', 'external'])->default('internal')->comment('連結類型');
            $table->string('link_target', 255)->nullable()->comment('連結目標 URL 或路由');
            $table->integer('order')->default(0)->comment('排序');
            $table->boolean('is_active')->default(true)->comment('是否啟用');
            $table->boolean('open_new_tab')->default(false)->comment('是否開新視窗');
            $table->string('icon', 100)->nullable()->comment('圖示 (Icon)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

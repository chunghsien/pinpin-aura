<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('header_footer_styles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_id')->comment('關聯主題 installed_themes.id')->constrained('installed_themes')->onDelete('cascade');
            $table->enum('type', ['header', 'footer'])->comment('樣式類型：header 或 footer');
            $table->string('name')->comment('樣式名稱，例如：經典標頭');
            $table->string('slug')->unique()->comment('樣式代碼，用於 Blade Component 呼叫');
            $table->boolean('is_active')->default(true)->comment('樣式是否啟用');
            $table->string('preview_image')->nullable()->comment('預覽圖 URL');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(new Expression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->index(['theme_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('header_footer_styles');
    }
};

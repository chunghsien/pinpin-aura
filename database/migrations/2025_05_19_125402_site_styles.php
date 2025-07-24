<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_styles', function (Blueprint $table) {
            $table->id();
            $table->string('use_type')->default('web')->comment('使用場景，例如：web, web-mobile, admin, admin-mobile');
            $table->foreignId('header_style_id')->nullable()->comment('對應 header_footer_styles.id')->constrained('header_footer_styles')->nullOnDelete();
            $table->foreignId('footer_style_id')->nullable()->comment('對應 header_footer_styles.id')->constrained('header_footer_styles')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(new Expression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->unique(['use_type']); // 保證同一場景只會有一組設定
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_styles');
    }
};

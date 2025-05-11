<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_styles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installed_theme_id')
                ->constrained('installed_themes');
            $table->string('name', 255);
            $table->text('properties')->nullable();
            $table->tinyInteger('is_active', false, true)->default(0)->comment('是否啟用');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(
                new Expression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::enableForeignKeyConstraints();
        Schema::dropIfExists('page_styles');
        Schema::disableForeignKeyConstraints();
    }
};

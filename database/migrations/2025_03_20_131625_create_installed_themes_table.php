<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('installed_themes', function (Blueprint $table) {
            $table->id();
            $table->string('use_type')->comment(
                '使用類型，當安裝更多主題時，確保每一個相同use_type + name 只會有一個會被啟用(is_active = 1)'
            );
            $table->string('name')->comment('樣板主題顯示名稱');
            $table->string('slug')->unique()->comment('樣板識別代號（例如 lezada）');
            $table->enum('source_type', ['local', 'marketplace', 'git'])->default('local')->comment('主題來源');
            $table->string('version')->nullable()->comment('樣板版本');
            $table->string('author')->nullable()->comment('樣板作者');
            $table->text('description')->nullable()->comment('主題說明');
            $table->tinyInteger('is_active', false, true)->default(0)->comment('是否為啟用中樣板');
            $table->timestamp('installed_at')->useCurrent()->comment('安裝時間');
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
        Schema::dropIfExists('installed_themes');
    }
};

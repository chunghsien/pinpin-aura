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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('theme_id', false, true)->default(0)->comment('主題ID');

            $table->string('index', 48)->comment('索引')->unique();
            $table->string('name', 128)->comment('名稱');
            $table->mediumInteger('sort', false, true)->default('16777215')->comment('排序');
            $table->string('is_allowed_methods', 128)->comment('允許的方法');
            $table->tinyInteger('is_active', false, false)->default('1')->comment('是否啟用');
            $table->softDeletesTz();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(new Expression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('language_locale', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained('languages', 'id');
            $table->foreignId('locale_id')->constrained('locales', 'id');
            $table->string('php_code', 16)->unique();
            $table->string('html_code', 16)->unique();
            $table->string('display_name', 32);
            $table->tinyInteger('is_active', false, true)->default(0)->comment('是否啟用');
            $table->unique(['language_id', 'locale_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_locale');
    }
};

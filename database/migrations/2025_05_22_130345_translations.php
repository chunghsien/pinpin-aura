<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('translatable_type'); // E.g., 'Menu', 'Product'
            $table->unsignedBigInteger('translatable_id');
            $table->string('locale', 10); // E.g., 'en_US', 'zh_TW'
            $table->string('key'); // E.g., 'name', 'description'
            $table->text('value'); // The translated content
            $table->timestamps();

            $table->index(['translatable_type', 'translatable_id']);
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};

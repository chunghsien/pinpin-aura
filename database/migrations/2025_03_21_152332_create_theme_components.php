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
        Schema::enableForeignKeyConstraints();
        Schema::create('theme_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installed_theme_id')->constrained('installed_themes', 'id');
            $table->enum('component_type', [
                'header',
                'footer',
                'sub_theme',
                'layout_overlay',
                'section',
                'row',
                'module'
            ])
                ->comment(
                    json_encode(
                        [
                            'header' => 'header 元件',
                            'footer' => 'footer 元件',
                            //['custom_sub_theme']
                            'sub_theme' => 'sub_theme元件',
                            'layout_overlay' => 'layout_overlay',
                            'section' => '部分',
                            'row' => '列',
                            'module' => '模塊，最基礎的html元件'
                        ]
                    )
                );
            $table->string('resolve_name', 255)->comment('livewire位置名稱');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(new Expression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::disableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::enableForeignKeyConstraints();
        Schema::dropIfExists('theme_components');
        Schema::disableForeignKeyConstraints();
    }
};

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->comment('國家幣別');
            $table->id();
            $table->string('code', 4)->comment('幣別代碼')->unique();
            $table->string('name', 255)->comment('幣別名稱(英文)');
            $table->unsignedTinyInteger('fraction_digits')->default(2)->comment('小數位數, 對應 ISO 4217 的 digits');
            $table->boolean('is_active')->default(FALSE)->comment('是否啟用');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(new Expression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};

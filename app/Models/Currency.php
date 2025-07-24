<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    // 對應資料表
    protected $table = 'currencies';

    // 可批次寫入欄位
    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    // 欄位型態轉換
    protected $casts = [
        'is_active' => 'boolean',
    ];
}

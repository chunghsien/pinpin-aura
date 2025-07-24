<?php

declare(strict_types=1);

namespace App\Enums;

enum MenuType: string
{
    case HEADER = 'header';
    case FOOTER = 'footer';
    case SIDE = 'side';
    case MOBILE = 'mobile';
    case TOPBAR = 'topbar';

    /**
     * 取得所有值的陣列
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * 取得選項陣列（用於表單選擇）
     */
    public static function options(): array
    {
        return [
            self::HEADER->value => '頂部導覽',
            self::FOOTER->value => '底部導覽',
            self::SIDE->value => '側邊導覽',
            self::MOBILE->value => '手機導覽',
            self::TOPBAR->value => '頂部工具列',
        ];
    }

    /**
     * 取得顯示名稱
     */
    public function label(): string
    {
        return match ($this) {
            self::HEADER => '頂部導覽',
            self::FOOTER => '底部導覽',
            self::SIDE => '側邊導覽',
            self::MOBILE => '手機導覽',
            self::TOPBAR => '頂部工具列',
        };
    }

    /**
     * 取得圖示
     */
    public function icon(): string
    {
        return match ($this) {
            self::HEADER => 'fas fa-bars',
            self::FOOTER => 'fas fa-grip-horizontal',
            self::SIDE => 'fas fa-bars-staggered',
            self::MOBILE => 'fas fa-mobile-alt',
            self::TOPBAR => 'fas fa-window-maximize',
        };
    }

    /**
     * 取得描述
     */
    public function description(): string
    {
        return match ($this) {
            self::HEADER => '網站頂部的主要導覽選單',
            self::FOOTER => '網站底部的輔助導覽選單',
            self::SIDE => '側邊欄的垂直導覽選單',
            self::MOBILE => '手機版的折疊導覽選單',
            self::TOPBAR => '頂部工具列的快速連結',
        };
    }

    /**
     * 檢查是否為主要導覽類型
     */
    public function isPrimary(): bool
    {
        return in_array($this, [self::HEADER, self::MOBILE], TRUE);
    }

    /**
     * 檢查是否為響應式類型
     */
    public function isResponsive(): bool
    {
        return in_array($this, [self::HEADER, self::MOBILE, self::SIDE], TRUE);
    }
}

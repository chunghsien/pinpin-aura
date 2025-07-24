<?php

declare(strict_types=1);

namespace App\Enums;

enum MenuStyle: string
{
    case MEGA_MENU = 'mega-menu';
    case SINGLE_COLUMN = 'single-column';
    case MULTI_LEVEL = 'multi-level';
    case SIMPLE = 'simple';

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
            self::MEGA_MENU->value => 'Mega Menu (大型多欄選單)',
            self::SINGLE_COLUMN->value => 'Single Column (單欄下拉)',
            self::MULTI_LEVEL->value => 'Multi Level (多層級選單)',
            self::SIMPLE->value => 'Simple (簡單項目)',
        ];
    }

    /**
     * 取得顯示名稱
     */
    public function label(): string
    {
        return match ($this) {
            self::MEGA_MENU => 'Mega Menu',
            self::SINGLE_COLUMN => 'Single Column',
            self::MULTI_LEVEL => 'Multi Level',
            self::SIMPLE => 'Simple',
        };
    }

    /**
     * 取得中文名稱
     */
    public function labelChinese(): string
    {
        return match ($this) {
            self::MEGA_MENU => '大型多欄選單',
            self::SINGLE_COLUMN => '單欄下拉選單',
            self::MULTI_LEVEL => '多層級選單',
            self::SIMPLE => '簡單選單項目',
        };
    }

    /**
     * 取得圖示
     */
    public function icon(): string
    {
        return match ($this) {
            self::MEGA_MENU => 'fas fa-th-large',
            self::SINGLE_COLUMN => 'fas fa-list',
            self::MULTI_LEVEL => 'fas fa-sitemap',
            self::SIMPLE => 'fas fa-minus',
        };
    }

    /**
     * 取得描述
     */
    public function description(): string
    {
        return match ($this) {
            self::MEGA_MENU => '適用於有大量子項目的選單，支援多欄位顯示和圖片',
            self::SINGLE_COLUMN => '適用於簡單的下拉選單，單欄顯示',
            self::MULTI_LEVEL => '適用於有多層級結構的選單',
            self::SIMPLE => '基本的選單項目，無特殊樣式',
        };
    }

    /**
     * 取得 CSS 類別
     */
    public function cssClass(): string
    {
        return match ($this) {
            self::MEGA_MENU => 'mega-menu',
            self::SINGLE_COLUMN => 'single-column-menu',
            self::MULTI_LEVEL => 'single-column-menu single-column-has-children',
            self::SIMPLE => '',
        };
    }

    /**
     * 取得子選單 CSS 類別
     */
    public function submenuCssClass(): string
    {
        return match ($this) {
            self::MEGA_MENU => 'sub-menu mega-sub-menu',
            self::SINGLE_COLUMN => 'sub-menu single-column-menu',
            self::MULTI_LEVEL => 'sub-menu multilevel-submenu',
            self::SIMPLE => 'sub-menu',
        };
    }

    /**
     * 檢查是否支援多欄位
     */
    public function supportsColumns(): bool
    {
        return $this === self::MEGA_MENU;
    }

    /**
     * 檢查是否支援圖片
     */
    public function supportsImages(): bool
    {
        return $this === self::MEGA_MENU;
    }

    /**
     * 檢查是否支援多層級
     */
    public function supportsMultiLevel(): bool
    {
        return in_array($this, [self::MULTI_LEVEL, self::MEGA_MENU], TRUE);
    }

    /**
     * 取得建議的欄位數量範圍
     */
    public function getColumnRange(): array
    {
        return match ($this) {
            self::MEGA_MENU => [3, 4, 5],
            default => [1],
        };
    }

    /**
     * 取得預設欄位數量
     */
    public function getDefaultColumns(): int
    {
        return match ($this) {
            self::MEGA_MENU => 4,
            default => 1,
        };
    }
}

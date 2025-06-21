<?php

namespace App\Models;

use App\Enums\MenuType;
use App\Enums\MenuStyle;
use App\Enums\LinkType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'link_type',
        'link_target',
        'order',
        'is_active',
        'open_new_tab',
        'icon',
        'menu_style',
        'mega_columns',
        'column_title',
        'image_url',
        'menu_image_url',
        'has_image',
        'css_class',
        'custom_attributes',
        'display_rules',
        'is_mega_column',
        'is_column_title',
        'description',
        'tooltip',
        'is_featured',
        'badge_text',
        'badge_color',
    ];

    protected $casts = [
        'type' => MenuType::class,
        'menu_style' => MenuStyle::class,
        'link_type' => LinkType::class,
        'is_active' => 'boolean',
        'open_new_tab' => 'boolean',
        'has_image' => 'boolean',
        'is_mega_column' => 'boolean',
        'is_column_title' => 'boolean',
        'is_featured' => 'boolean',
        'custom_attributes' => 'array',
        'display_rules' => 'array',
        'order' => 'integer',
        'mega_columns' => 'integer',
    ];

    // 注意：常數已被 PHP enum 取代，保留是為了向後相容性
    // 新代碼請使用 MenuType::HEADER, MenuStyle::MEGA_MENU, LinkType::INTERNAL 等

    /**
     * 關聯：父選單
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * 關聯：子選單
     */
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('order');
    }

    /**
     * 關聯：所有子選單（包含未啟用的）
     */
    public function allChildren(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->orderBy('order');
    }

    /**
     * 遞歸取得所有子選單
     */
    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * Scope: 啟用的選單
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: 依類型篩選
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: 頂層選單
     */
    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope: 依樣式篩選
     */
    public function scopeOfStyle(Builder $query, string $style): Builder
    {
        return $query->where('menu_style', $style);
    }

    /**
     * Scope: Mega Menu
     */
    public function scopeMegaMenu(Builder $query): Builder
    {
        return $query->where('menu_style', MenuStyle::MEGA_MENU->value);
    }

    /**
     * Scope: 特色選單
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * 檢查是否有子選單
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * 檢查是否為頂層選單
     */
    public function isTopLevel(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * 檢查是否為 Mega Menu
     */
    public function isMegaMenu(): bool
    {
        return $this->menu_style === MenuStyle::MEGA_MENU;
    }

    /**
     * 檢查是否為多層級選單
     */
    public function isMultiLevel(): bool
    {
        return $this->menu_style === MenuStyle::MULTI_LEVEL;
    }

    /**
     * 檢查是否為欄位標題
     */
    public function isColumnTitle(): bool
    {
        return $this->is_column_title;
    }

    /**
     * 取得完整的 CSS 類別
     */
    public function getCssClasses(): string
    {
        $classes = [];

        // 基本類別
        if ($this->hasChildren()) {
            $classes[] = 'menu-item-has-children';
        }

        // 樣式類別
        switch ($this->menu_style) {
            case MenuStyle::MEGA_MENU:
                $classes[] = 'mega-menu';
                if ($this->mega_columns) {
                    $classes[] = "mega-menu-column-{$this->mega_columns}";
                }
                break;
            case MenuStyle::SINGLE_COLUMN:
                $classes[] = 'single-column-menu';
                break;
            case MenuStyle::MULTI_LEVEL:
                $classes[] = 'single-column-menu single-column-has-children';
                break;
        }

        // 垂直選單類別
        if ($this->type === MenuType::SIDE && $this->hasChildren()) {
            $classes[] = 'has-children';
        }

        // 自定義類別
        if ($this->css_class) {
            $classes[] = $this->css_class;
        }

        // 特殊狀態類別
        if ($this->is_featured) {
            $classes[] = 'featured-menu-item';
        }

        if ($this->is_mega_column) {
            $classes[] = 'mega-column-title';
        }

        return implode(' ', array_filter($classes));
    }

    /**
     * 取得子選單的 CSS 類別
     */
    public function getSubmenuCssClasses(): string
    {
        $classes = ['sub-menu'];

        switch ($this->menu_style) {
            case MenuStyle::MEGA_MENU:
                $classes[] = 'mega-sub-menu';
                break;
            case MenuStyle::SINGLE_COLUMN:
                $classes[] = 'single-column-menu';
                break;
            case MenuStyle::MULTI_LEVEL:
                $classes[] = 'multilevel-submenu';
                break;
        }

        return implode(' ', $classes);
    }

    /**
     * 取得連結目標
     */
    public function getUrl(): string
    {
        if ($this->link_type === LinkType::EXTERNAL) {
            return $this->link_target;
        }

        // 內部連結處理
        if (str_starts_with($this->link_target, '/')) {
            return $this->link_target;
        }

        // 如果是路由名稱，嘗試生成 URL
        try {
            return route($this->link_target);
        } catch (\Exception $e) {
            return $this->link_target ?: '#';
        }
    }

    /**
     * 檢查是否應該顯示在指定裝置
     */
    public function shouldDisplayOn(string $device): bool
    {
        if (empty($this->display_rules)) {
            return true;
        }

        $rules = $this->display_rules;

        if (isset($rules['devices'])) {
            return in_array($device, $rules['devices']);
        }

        return true;
    }

    /**
     * 取得階層式選單結構
     */
    public static function getHierarchicalMenu(MenuType|string $type = MenuType::HEADER): array
    {
        $typeValue = $type instanceof MenuType ? $type->value : $type;

        return self::active()
            ->ofType($typeValue)
            ->topLevel()
            ->with('childrenRecursive')
            ->orderBy('order')
            ->get()
            ->toArray();
    }

    /**
     * 取得扁平化選單結構
     */
    public static function getFlatMenu(MenuType|string $type = MenuType::HEADER): array
    {
        $typeValue = $type instanceof MenuType ? $type->value : $type;

        return self::active()
            ->ofType($typeValue)
            ->orderBy('order')
            ->get()
            ->toArray();
    }

    /**
     * 取得 Mega Menu 結構
     */
    public static function getMegaMenus(MenuType|string $type = MenuType::HEADER): array
    {
        $typeValue = $type instanceof MenuType ? $type->value : $type;

        return self::active()
            ->ofType($typeValue)
            ->megaMenu()
            ->topLevel()
            ->with('childrenRecursive')
            ->orderBy('order')
            ->get()
            ->toArray();
    }
}

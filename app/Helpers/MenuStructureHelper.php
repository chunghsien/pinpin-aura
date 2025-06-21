<?php

namespace App\Helpers;

class MenuStructureHelper
{
    /**
     * 取得預設選單結構資料
     */
    public static function getDefaultMenuStructure(): array
    {
        // 取得基礎路徑
        $basePath = function_exists('base_path') ? base_path() : dirname(__DIR__, 2);
        $themePath = $basePath . '/packages/pinpin/themes-lezada/config';

        // 優先載入完整的 header 結構
        if (file_exists($themePath . '/complete-header-structure.php')) {
            return include $themePath . '/complete-header-structure.php';
        }

        // 回退到原有的分割檔案
        $structure1 = [];
        $structure2 = [];

        if (file_exists($themePath . '/menu-structure.php')) {
            $structure1 = include $themePath . '/menu-structure.php';
        }

        if (file_exists($themePath . '/menu-structure-part2.php')) {
            $structure2 = include $themePath . '/menu-structure-part2.php';
        }

        return array_merge($structure1, $structure2);
    }

    /**
     * 將硬編碼的選單結構轉換成符合 menus 資料表的扁平化格式
     */
    public static function convertToMenusFormat(): array
    {
        // 載入主題包中的選單結構設定
        $allMenus = self::getDefaultMenuStructure();

        $convertedMenus = [];
        $idCounter = 1;

        foreach ($allMenus as $topLevelMenu) {
            // 處理頂層選單
            $topMenu = self::convertMenuItem($topLevelMenu, null, $idCounter++);
            $convertedMenus[] = $topMenu;

            // 處理子選單
            if (isset($topLevelMenu['children']) && is_array($topLevelMenu['children'])) {
                $childMenus = self::processChildren($topLevelMenu['children'], $topMenu['id'], $idCounter);
                $convertedMenus = array_merge($convertedMenus, $childMenus['menus']);
                $idCounter = $childMenus['counter'];
            }
        }

        return $convertedMenus;
    }

    /**
     * 轉換單個選單項目
     */
    private static function convertMenuItem(array $item, ?int $parentId, int $id): array
    {
        return [
            'id' => $id,
            'name' => $item['title'],
            'type' => $item['type'] ?? 'header',
            'parent_id' => $parentId,
            'link_type' => $item['link_type'] ?? 'internal',
            'link_target' => $item['url'] ?? '#',
            'order' => $item['sort_order'] ?? 0,
            'is_active' => ($item['status'] ?? 'active') === 'active',
            'open_new_tab' => ($item['target'] ?? '_self') === '_blank',
            'icon' => $item['icon'] ?? null,

            // 新增的進階欄位
            'menu_style' => $item['menu_style'] ?? 'simple',
            'mega_columns' => $item['mega_columns'] ?? null,
            'column_title' => $item['is_column_title'] ?? false ? $item['title'] : null,
            'image_url' => $item['image_url'] ?? null,
            'menu_image_url' => $item['menu_image_url'] ?? null,
            'has_image' => $item['has_image'] ?? false,
            'css_class' => $item['css_class'] ?? null,
            'is_mega_column' => $item['is_mega_column'] ?? false,
            'is_column_title' => $item['is_column_title'] ?? false,
            'is_featured' => $item['is_featured'] ?? false,
            'description' => $item['description'] ?? null,

            // 額外的樣式資訊（保持向後相容）
            'meta' => [
                'css_class' => $item['css_class'] ?? null,
                'description' => $item['description'] ?? null,
                'target' => $item['target'] ?? '_self',
                'menu_type' => $item['menu_style'] ?? null,
                'mega_columns' => $item['mega_columns'] ?? null,
                'is_menu_image' => $item['is_menu_image'] ?? false,
                'menu_image_url' => $item['menu_image_url'] ?? null,
            ]
        ];
    }

    /**
     * 遞歸處理子選單
     */
    private static function processChildren(array $children, int $parentId, int &$counter): array
    {
        $processedMenus = [];

        foreach ($children as $child) {
            // 處理當前子選單
            $childMenu = self::convertMenuItem($child, $parentId, $counter++);
            $processedMenus[] = $childMenu;

            // 如果有更深層的子選單，遞歸處理
            if (isset($child['children']) && is_array($child['children'])) {
                $deeperChildren = self::processChildren($child['children'], $childMenu['id'], $counter);
                $processedMenus = array_merge($processedMenus, $deeperChildren['menus']);
                $counter = $deeperChildren['counter'];
            }
        }

        return [
            'menus' => $processedMenus,
            'counter' => $counter
        ];
    }

    /**
     * 獲取桌面版導覽選單格式
     */
    public static function getDesktopMenuFormat(): array
    {
        $menus = self::convertToMenusFormat();
        return self::buildHierarchicalStructure($menus, 'header');
    }

    /**
     * 獲取手機版導覽選單格式
     */
    public static function getMobileMenuFormat(): array
    {
        $menus = self::convertToMenusFormat();
        // 手機版可能需要不同的結構或過濾條件
        return self::buildHierarchicalStructure($menus, 'header', true);
    }

    /**
     * 獲取垂直選單格式
     */
    public static function getVerticalMenuFormat(): array
    {
        $menus = self::convertToMenusFormat();
        // 垂直選單可能需要扁平化或簡化的結構
        return self::buildHierarchicalStructure($menus, 'header');
    }

    /**
     * 建立階層式結構
     */
    private static function buildHierarchicalStructure(array $menus, string $type, bool $simplify = false): array
    {
        // 篩選指定類型的選單
        $filteredMenus = array_filter($menus, function ($menu) use ($type) {
            return $menu['type'] === $type;
        });

        // 建立父子關係映射
        $menuMap = [];
        $topLevelMenus = [];

        foreach ($filteredMenus as $menu) {
            $menuMap[$menu['id']] = $menu;
            $menuMap[$menu['id']]['children'] = [];

            if ($menu['parent_id'] === null) {
                $topLevelMenus[] = &$menuMap[$menu['id']];
            }
        }

        // 建立子選單關係
        foreach ($filteredMenus as $menu) {
            if ($menu['parent_id'] !== null && isset($menuMap[$menu['parent_id']])) {
                $menuMap[$menu['parent_id']]['children'][] = &$menuMap[$menu['id']];
            }
        }

        // 根據 order 排序
        usort($topLevelMenus, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        // 遞歸排序子選單
        self::sortChildrenRecursively($topLevelMenus);

        // 如果需要簡化（如手機版），移除一些複雜的選單結構
        if ($simplify) {
            $topLevelMenus = self::simplifyMenuStructure($topLevelMenus);
        }

        return $topLevelMenus;
    }

    /**
     * 遞歸排序子選單
     */
    private static function sortChildrenRecursively(array &$menus): void
    {
        foreach ($menus as &$menu) {
            if (!empty($menu['children'])) {
                usort($menu['children'], function ($a, $b) {
                    return $a['order'] <=> $b['order'];
                });
                self::sortChildrenRecursively($menu['children']);
            }
        }
    }

    /**
     * 簡化選單結構（用於手機版等）
     */
    private static function simplifyMenuStructure(array $menus): array
    {
        foreach ($menus as &$menu) {
            // 手機版可能只顯示前兩層選單
            if (!empty($menu['children'])) {
                foreach ($menu['children'] as &$child) {
                    if (!empty($child['children']) && count($child['children']) > 5) {
                        // 限制子選單數量
                        $child['children'] = array_slice($child['children'], 0, 5);
                        $child['children'][] = [
                            'id' => 'more_' . $child['id'],
                            'name' => '更多...',
                            'link_target' => $child['link_target'],
                            'meta' => ['is_more_link' => true]
                        ];
                    }
                }
            }
        }

        return $menus;
    }

    /**
     * 檢查選單項目是否為 Mega Menu
     */
    public static function isMegaMenu(array $menu): bool
    {
        // 檢查 meta 中的 menu_type
        if (isset($menu['meta']['menu_type']) && str_contains($menu['meta']['menu_type'], 'mega-menu')) {
            return true;
        }

        // 檢查 meta 中的 css_class
        if (isset($menu['meta']['css_class']) && str_contains($menu['meta']['css_class'], 'mega-menu')) {
            return true;
        }

        // 檢查是否有多個子選單且子選單有 mega-menu-column 類別
        if (!empty($menu['children']) && count($menu['children']) >= 3) {
            foreach ($menu['children'] as $child) {
                if (isset($child['meta']['css_class']) && str_contains($child['meta']['css_class'], 'mega-menu-column')) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * 獲取選單的 CSS 類別
     */
    public static function getMenuCssClass(array $menu): string
    {
        $classes = [];

        if (!empty($menu['children'])) {
            $classes[] = 'menu-item-has-children';
        }

        if (isset($menu['meta']['css_class'])) {
            $classes[] = $menu['meta']['css_class'];
        }

        if (self::isMegaMenu($menu)) {
            $classes[] = 'mega-menu';
            if (isset($menu['meta']['mega_columns'])) {
                $classes[] = 'mega-menu-column-' . $menu['meta']['mega_columns'];
            }
        }

        return implode(' ', $classes);
    }

    /**
     * 獲取子選單的 CSS 類別
     */
    public static function getSubmenuCssClass(array $menu, int $depth = 1): string
    {
        $classes = ['sub-menu'];

        if ($depth > 1) {
            $classes[] = 'multilevel-submenu';
        }

        if (self::isMegaMenu($menu)) {
            $classes[] = 'mega-sub-menu';
        }

        if (isset($menu['meta']['menu_type'])) {
            if ($menu['meta']['menu_type'] === 'single-column') {
                $classes[] = 'single-column-menu';
            } elseif ($menu['meta']['menu_type'] === 'single-column-has-children') {
                $classes[] = 'single-column-menu single-column-has-children';
            }
        }

        return implode(' ', $classes);
    }
}

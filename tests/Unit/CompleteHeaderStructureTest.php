<?php

namespace Tests\Unit;

use App\Helpers\MenuStructureHelper;
use Tests\TestCase;

class CompleteHeaderStructureTest extends TestCase
{
    public function test_complete_header_structure_loading()
    {
        $convertedMenus = MenuStructureHelper::convertToMenusFormat();

        // 驗證總數量
        $this->assertGreaterThan(100, count($convertedMenus), '應該有超過 100 個選單項目');

        // 驗證頂層選單
        $topLevelMenus = array_filter($convertedMenus, function ($menu) {
            return $menu['parent_id'] === null;
        });

        $this->assertCount(5, $topLevelMenus, '應該有 5 個頂層選單');

        // 驗證選單名稱
        $topLevelNames = array_column($topLevelMenus, 'name');
        $expectedNames = ['Home', 'Shop', 'Elements', 'Pages', 'Blog'];

        foreach ($expectedNames as $name) {
            $this->assertContains($name, $topLevelNames, "應該包含 {$name} 選單");
        }
    }

    public function test_mega_menu_structures()
    {
        $desktopMenus = MenuStructureHelper::getDesktopMenuFormat();

        // 驗證 Mega Menu
        $megaMenus = array_filter($desktopMenus, function ($menu) {
            return MenuStructureHelper::isMegaMenu($menu);
        });

        $this->assertCount(3, $megaMenus, '應該有 3 個 Mega Menu (Home, Shop, Elements)');

        foreach ($megaMenus as $menu) {
            $this->assertArrayHasKey('mega_columns', $menu);
            $this->assertGreaterThan(0, $menu['mega_columns'], 'Mega Menu 應該有欄位數');
            $this->assertGreaterThan(2, count($menu['children']), 'Mega Menu 應該有多個子項目');
        }
    }

    public function test_home_mega_menu_structure()
    {
        $desktopMenus = MenuStructureHelper::getDesktopMenuFormat();
        $homeMenu = null;

        foreach ($desktopMenus as $menu) {
            if ($menu['name'] === 'Home') {
                $homeMenu = $menu;
                break;
            }
        }

        $this->assertNotNull($homeMenu, '應該找到 Home 選單');
        $this->assertEquals('mega-menu', $homeMenu['menu_style']);
        $this->assertEquals(5, $homeMenu['mega_columns']);
        $this->assertCount(5, $homeMenu['children'], 'Home 應該有 5 個欄位');

        // 檢查是否有圖片欄位
        $hasMenuImage = false;
        foreach ($homeMenu['children'] as $child) {
            if ($child['meta']['is_menu_image'] ?? false) {
                $hasMenuImage = true;
                $this->assertNotEmpty($child['meta']['menu_image_url'], '選單圖片應該有 URL');
                break;
            }
        }
        $this->assertTrue($hasMenuImage, 'Home Mega Menu 應該有選單圖片');
    }

    public function test_shop_mega_menu_structure()
    {
        $desktopMenus = MenuStructureHelper::getDesktopMenuFormat();
        $shopMenu = null;

        foreach ($desktopMenus as $menu) {
            if ($menu['name'] === 'Shop') {
                $shopMenu = $menu;
                break;
            }
        }

        $this->assertNotNull($shopMenu, '應該找到 Shop 選單');
        $this->assertEquals('mega-menu', $shopMenu['menu_style']);
        $this->assertEquals(4, $shopMenu['mega_columns']);
        $this->assertCount(4, $shopMenu['children'], 'Shop 應該有 4 個欄位');

        // 檢查欄位標題
        $columnTitles = 0;
        foreach ($shopMenu['children'] as $child) {
            if ($child['is_column_title']) {
                $columnTitles++;
            }
        }
        $this->assertGreaterThan(0, $columnTitles, 'Shop Mega Menu 應該有欄位標題');
    }

    public function test_elements_mega_menu_structure()
    {
        $desktopMenus = MenuStructureHelper::getDesktopMenuFormat();
        $elementsMenu = null;

        foreach ($desktopMenus as $menu) {
            if ($menu['name'] === 'Elements') {
                $elementsMenu = $menu;
                break;
            }
        }

        $this->assertNotNull($elementsMenu, '應該找到 Elements 選單');
        $this->assertEquals('mega-menu', $elementsMenu['menu_style']);
        $this->assertEquals(5, $elementsMenu['mega_columns']);
        $this->assertCount(5, $elementsMenu['children'], 'Elements 應該有 5 個欄位');
    }

    public function test_pages_single_column_menu()
    {
        $desktopMenus = MenuStructureHelper::getDesktopMenuFormat();
        $pagesMenu = null;

        foreach ($desktopMenus as $menu) {
            if ($menu['name'] === 'Pages') {
                $pagesMenu = $menu;
                break;
            }
        }

        $this->assertNotNull($pagesMenu, '應該找到 Pages 選單');
        $this->assertEquals('single-column', $pagesMenu['menu_style']);
        $this->assertFalse(MenuStructureHelper::isMegaMenu($pagesMenu), 'Pages 不應該是 Mega Menu');
        $this->assertGreaterThan(5, count($pagesMenu['children']), 'Pages 應該有多個子項目');
    }

    public function test_blog_multi_level_menu()
    {
        $desktopMenus = MenuStructureHelper::getDesktopMenuFormat();
        $blogMenu = null;

        foreach ($desktopMenus as $menu) {
            if ($menu['name'] === 'Blog') {
                $blogMenu = $menu;
                break;
            }
        }

        $this->assertNotNull($blogMenu, '應該找到 Blog 選單');
        $this->assertEquals('multi-level', $blogMenu['menu_style']);
        $this->assertFalse(MenuStructureHelper::isMegaMenu($blogMenu), 'Blog 不應該是 Mega Menu');

        // 檢查多層級結構
        $hasMultiLevel = false;
        foreach ($blogMenu['children'] as $child) {
            if (!empty($child['children'])) {
                $hasMultiLevel = true;
                $this->assertGreaterThan(0, count($child['children']), '應該有第三層選單');
                break;
            }
        }
        $this->assertTrue($hasMultiLevel, 'Blog 應該有多層級結構');
    }

    public function test_menu_images_and_features()
    {
        $convertedMenus = MenuStructureHelper::convertToMenusFormat();

        // 檢查有圖片的選單項目
        $menusWithImages = array_filter($convertedMenus, function ($menu) {
            return $menu['has_image'] === true;
        });

        $this->assertGreaterThan(10, count($menusWithImages), '應該有多個帶圖片的選單項目');

        // 檢查選單圖片
        $menuImages = array_filter($convertedMenus, function ($menu) {
            return !empty($menu['menu_image_url']);
        });

        $this->assertGreaterThan(0, count($menuImages), '應該有選單圖片項目');

        // 檢查欄位標題
        $columnTitles = array_filter($convertedMenus, function ($menu) {
            return $menu['is_column_title'] === true;
        });

        $this->assertGreaterThan(5, count($columnTitles), '應該有多個欄位標題');
    }

    public function test_menu_hierarchy_depth()
    {
        $desktopMenus = MenuStructureHelper::getDesktopMenuFormat();

        $maxDepth = 0;
        foreach ($desktopMenus as $menu) {
            $depth = $this->calculateMenuDepth($menu);
            $maxDepth = max($maxDepth, $depth);
        }

        $this->assertGreaterThanOrEqual(3, $maxDepth, '選單應該至少有 3 層深度');
    }

    private function calculateMenuDepth(array $menu, int $currentDepth = 1): int
    {
        $maxDepth = $currentDepth;

        if (!empty($menu['children'])) {
            foreach ($menu['children'] as $child) {
                $childDepth = $this->calculateMenuDepth($child, $currentDepth + 1);
                $maxDepth = max($maxDepth, $childDepth);
            }
        }

        return $maxDepth;
    }

    public function test_menu_css_classes_and_styles()
    {
        $desktopMenus = MenuStructureHelper::getDesktopMenuFormat();

        foreach ($desktopMenus as $menu) {
            // 測試 CSS 類別生成
            $cssClass = MenuStructureHelper::getMenuCssClass($menu);
            $this->assertIsString($cssClass);

            if (MenuStructureHelper::isMegaMenu($menu)) {
                $this->assertStringContainsString('mega-menu', $cssClass, 'Mega Menu 應該包含 mega-menu 類別');
            }

            // 測試子選單 CSS 類別
            $submenuClass = MenuStructureHelper::getSubmenuCssClass($menu);
            $this->assertIsString($submenuClass);
            $this->assertStringContainsString('sub-menu', $submenuClass, '子選單應該包含 sub-menu 類別');
        }
    }
}

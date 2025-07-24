<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Helpers\MenuStructureHelper;
use Tests\TestCase;

class MenuStructureHelperTest extends TestCase
{
    public function test_basic_conversion_functionality()
    {
        $convertedMenus = MenuStructureHelper::convertToMenusFormat();

        $this->assertIsArray($convertedMenus);
        $this->assertGreaterThan(0, count($convertedMenus));

        // 檢查頂層選單數量
        $topLevelMenus = array_filter($convertedMenus, function ($menu) {
            return $menu['parent_id'] === NULL;
        });

        $this->assertGreaterThan(0, count($topLevelMenus));

        // 檢查第一個選單的基本結構
        $firstMenu = reset($convertedMenus);
        $this->assertArrayHasKey('id', $firstMenu);
        $this->assertArrayHasKey('name', $firstMenu);
        $this->assertArrayHasKey('type', $firstMenu);
        $this->assertArrayHasKey('link_target', $firstMenu);
    }

    public function test_desktop_menu_format()
    {
        $desktopMenus = MenuStructureHelper::getDesktopMenuFormat();

        $this->assertIsArray($desktopMenus);
        $this->assertGreaterThan(0, count($desktopMenus));

        // 檢查第一個選單的結構
        if (! empty($desktopMenus)) {
            $firstMenu = $desktopMenus[0];
            $this->assertArrayHasKey('name', $firstMenu);
            $this->assertArrayHasKey('children', $firstMenu);
        }
    }

    public function test_mobile_menu_format()
    {
        $mobileMenus = MenuStructureHelper::getMobileMenuFormat();

        $this->assertIsArray($mobileMenus);
        $this->assertGreaterThan(0, count($mobileMenus));
    }

    public function test_vertical_menu_format()
    {
        $verticalMenus = MenuStructureHelper::getVerticalMenuFormat();

        $this->assertIsArray($verticalMenus);
        $this->assertGreaterThan(0, count($verticalMenus));
    }

    public function test_css_class_generation()
    {
        $desktopMenus = MenuStructureHelper::getDesktopMenuFormat();

        if (! empty($desktopMenus)) {
            $firstMenu = $desktopMenus[0];

            // 測試選單 CSS 類別
            $menuClass = MenuStructureHelper::getMenuCssClass($firstMenu);
            $this->assertIsString($menuClass);

            // 測試子選單 CSS 類別
            $submenuClass = MenuStructureHelper::getSubmenuCssClass($firstMenu);
            $this->assertIsString($submenuClass);

            // 測試 Mega Menu 檢查
            $isMega = MenuStructureHelper::isMegaMenu($firstMenu);
            $this->assertIsBool($isMega);
        }
    }

    public function test_menu_hierarchy_structure()
    {
        $desktopMenus = MenuStructureHelper::getDesktopMenuFormat();

        // 檢查是否有階層結構
        $hasChildren = FALSE;
        foreach ($desktopMenus as $menu) {
            if (! empty($menu['children'])) {
                $hasChildren = TRUE;

                // 檢查子選單結構
                foreach ($menu['children'] as $child) {
                    $this->assertArrayHasKey('name', $child);
                    $this->assertArrayHasKey('link_target', $child);
                }

                break;
            }
        }

        $this->assertTrue($hasChildren, '應該至少有一個選單包含子項目');
    }

    public function test_menu_types_and_properties()
    {
        $convertedMenus = MenuStructureHelper::convertToMenusFormat();

        foreach ($convertedMenus as $menu) {
            // 檢查必要欄位
            $this->assertArrayHasKey('id', $menu);
            $this->assertArrayHasKey('name', $menu);
            $this->assertArrayHasKey('type', $menu);
            $this->assertArrayHasKey('link_type', $menu);
            $this->assertArrayHasKey('link_target', $menu);
            $this->assertArrayHasKey('is_active', $menu);

            // 檢查資料類型
            $this->assertIsInt($menu['id']);
            $this->assertIsString($menu['name']);
            $this->assertIsString($menu['type']);
            $this->assertIsString($menu['link_type']);
            $this->assertIsBool($menu['is_active']);
        }
    }

    public function test_mega_menu_detection()
    {
        $desktopMenus = MenuStructureHelper::getDesktopMenuFormat();

        $megaMenuFound = FALSE;
        foreach ($desktopMenus as $menu) {
            if (MenuStructureHelper::isMegaMenu($menu)) {
                $megaMenuFound = TRUE;

                // Mega Menu 應該有多個子項目
                $this->assertGreaterThan(2, count($menu['children'] ?? []));

                break;
            }
        }

        // 基於我們的測試資料，應該至少有一個 Mega Menu
        $this->assertTrue($megaMenuFound, '應該至少有一個 Mega Menu');
    }

    public function test_menu_ordering()
    {
        $desktopMenus = MenuStructureHelper::getDesktopMenuFormat();

        // 檢查頂層選單是否按 order 排序
        for ($i = 1; $i < count($desktopMenus); $i++) {
            $prevOrder = $desktopMenus[$i - 1]['order'] ?? 0;
            $currentOrder = $desktopMenus[$i]['order'] ?? 0;

            $this->assertLessThanOrEqual($currentOrder, $prevOrder, '選單應該按 order 欄位排序');
        }
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature;

use Livewire\Livewire;
use Pinpin\ThemesLezada\Http\Livewire\Headers\Partials\Bottom\Navigation;
use Tests\TestCase;

class NavigationComponentTest extends TestCase
{
    public function test_navigation_component_renders_successfully()
    {
        // 測試 Navigation 元件是否能成功渲染
        $component = Livewire::test(Navigation::class);

        $component->assertOk();
        $component->assertViewIs('themes-lezada::livewire.headers.partials.bottom.navigation');
    }

    public function test_navigation_component_has_menu_items()
    {
        // 測試是否有選單項目
        $component = Livewire::test(Navigation::class);

        $component->assertViewHas('menuItems');
        $component->assertViewHas('useDatabase');

        // 驗證有選單項目（靜態或資料庫）
        $menuItems = $component->get('menuItems');
        $this->assertNotNull($menuItems);
    }

    public function test_navigation_component_handles_overlay_mode()
    {
        // 測試 Overlay Navigation 模式
        $component = Livewire::test(Navigation::class, ['isOverlayNav' => TRUE]);

        $component->assertOk();
        $this->assertTrue($component->get('isOverlayNav'));
    }

    public function test_navigation_component_methods_exist()
    {
        // 測試必要的方法是否存在
        $component = Livewire::test(Navigation::class);
        $instance = $component->instance();

        $this->assertTrue(method_exists($instance, 'getCssClasses'));
        $this->assertTrue(method_exists($instance, 'getUrl'));
        $this->assertTrue(method_exists($instance, 'hasChildren'));
        $this->assertTrue(method_exists($instance, 'isMegaMenu'));
        $this->assertTrue(method_exists($instance, 'isColumnTitle'));
        $this->assertTrue(method_exists($instance, 'getSubmenuCssClasses'));
    }
}

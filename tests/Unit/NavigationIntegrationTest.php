<?php

namespace Tests\Unit;

use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Menu;
use Pinpin\ThemesLezada\Http\Livewire\Headers\Partials\Bottom\Navigation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class NavigationIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 手動建立 menus 資料表結構
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('選單項目名稱');
            $table->string('type', 50)->default('header')->comment('選單位置類型');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('父選單 ID');
            $table->string('link_type', 50)->default('internal')->comment('連結類型');
            $table->string('link_target', 255)->nullable()->comment('連結目標 URL 或路由');
            $table->integer('order')->default(0)->comment('排序');
            $table->boolean('is_active')->default(true)->comment('是否啟用');
            $table->boolean('open_new_tab')->default(false)->comment('是否開新視窗');
            $table->string('icon', 100)->nullable()->comment('圖示 (Icon)');
            $table->timestamps();

            // 手動建立外鍵關聯
            $table->foreign('parent_id')->references('id')->on('menus')->nullOnDelete();
        });
    }

    public function test_it_can_render_navigation_component_with_static_data()
    {
        // 測試沒有資料庫資料時使用靜態資料
        $component = Livewire::test(Navigation::class, []);

        $component->assertOk();
        $component->assertViewHas('menuItems');
        $component->assertViewHas('useDatabase', false);

        // 驗證靜態資料載入成功
        $this->assertNotEmpty($component->get('menuItems'));
    }

    public function test_it_can_render_navigation_component_with_database_data()
    {
        // 建立測試選單資料
        $homeMenu = Menu::create([
            'name' => 'Home',
            'type' => 'header',
            'link_type' => 'internal',
            'link_target' => '/',
            'order' => 1,
            'is_active' => true,
        ]);

        Menu::create([
            'name' => 'About',
            'type' => 'header',
            'parent_id' => $homeMenu->id,
            'link_type' => 'internal',
            'link_target' => '/about',
            'order' => 1,
            'is_active' => true,
        ]);

        $component = Livewire::test(Navigation::class, []);

        $component->assertOk();
        $component->assertViewHas('menuItems');
        $component->assertViewHas('useDatabase', true);

        // 驗證資料庫資料載入成功
        $menuItems = $component->get('menuItems');
        $this->assertCount(1, $menuItems);
        $this->assertEquals('Home', $menuItems->first()->name);
    }

    public function test_it_can_generate_basic_css_classes()
    {
        $menuWithChildren = Menu::create([
            'name' => 'Products',
            'type' => 'header',
            'link_type' => 'internal',
            'link_target' => '/products',
            'order' => 1,
            'is_active' => true,
        ]);

        Menu::create([
            'name' => 'Category 1',
            'type' => 'header',
            'parent_id' => $menuWithChildren->id,
            'link_type' => 'internal',
            'link_target' => '/products/category-1',
            'order' => 1,
            'is_active' => true,
        ]);

        $component = Livewire::test(Navigation::class, []);

        $menuItems = $component->get('menuItems');
        $firstItem = $menuItems->first();

        $cssClasses = $component->instance()->getCssClasses($firstItem);

        $this->assertStringContainsString('menu-item-has-children', $cssClasses);
    }

    public function test_it_can_generate_correct_urls()
    {
        $internalMenu = Menu::create([
            'name' => 'About',
            'type' => 'header',
            'link_type' => 'internal',
            'link_target' => '/about',
            'order' => 1,
            'is_active' => true,
        ]);

        $externalMenu = Menu::create([
            'name' => 'External Link',
            'type' => 'header',
            'link_type' => 'external',
            'link_target' => 'https://example.com',
            'order' => 2,
            'is_active' => true,
        ]);

        $component = Livewire::test(Navigation::class, []);

        $menuItems = $component->get('menuItems');

        $this->assertEquals('/about', $component->instance()->getUrl($menuItems->first()));
        $this->assertEquals('https://example.com', $component->instance()->getUrl($menuItems->last()));
    }

    public function test_it_handles_overlay_navigation_mode()
    {
        Menu::create([
            'name' => 'Home',
            'type' => 'header',
            'link_type' => 'internal',
            'link_target' => '/',
            'order' => 1,
            'is_active' => true,
        ]);

        $component = Livewire::test(Navigation::class, ['isOverlayNav' => true]);

        $component->assertOk();
        $this->assertTrue($component->get('isOverlayNav'));
    }

    public function test_it_handles_static_data_structure()
    {
        // 測試靜態資料的結構是否正確
        $component = Livewire::test(Navigation::class, []);

        $menuItems = $component->get('menuItems');

        // 應該有靜態資料
        $this->assertNotEmpty($menuItems);

        // 檢查第一個選單項目是否有正確的屬性
        $firstItem = $menuItems->first();
        $this->assertObjectHasProperty('name', $firstItem);
        $this->assertObjectHasProperty('link_target', $firstItem);
        $this->assertObjectHasProperty('children', $firstItem);
    }

    public function test_it_loads_hierarchical_menu_structure()
    {
        // 建立多層級選單結構
        $topMenu = Menu::create([
            'name' => 'Shop',
            'type' => 'header',
            'link_type' => 'internal',
            'link_target' => '/shop',
            'order' => 1,
            'is_active' => true,
        ]);

        $subMenu = Menu::create([
            'name' => 'Category',
            'type' => 'header',
            'parent_id' => $topMenu->id,
            'link_type' => 'internal',
            'link_target' => '/shop/category',
            'order' => 1,
            'is_active' => true,
        ]);

        Menu::create([
            'name' => 'Subcategory',
            'type' => 'header',
            'parent_id' => $subMenu->id,
            'link_type' => 'internal',
            'link_target' => '/shop/category/subcategory',
            'order' => 1,
            'is_active' => true,
        ]);

        $component = Livewire::test(Navigation::class, []);

        $menuItems = $component->get('menuItems');
        $topItem = $menuItems->first();

        $this->assertTrue($component->instance()->hasChildren($topItem));
        $this->assertCount(1, $topItem->children);
        $this->assertEquals('Category', $topItem->children->first()->name);
    }
}

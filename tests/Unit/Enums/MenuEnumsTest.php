<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\LinkType;
use App\Enums\MenuStyle;
use App\Enums\MenuType;
use PHPUnit\Framework\TestCase;

class MenuEnumsTest extends TestCase
{
    public function test_menu_type_enum_values()
    {
        $this->assertEquals('header', MenuType::HEADER->value);
        $this->assertEquals('footer', MenuType::FOOTER->value);
        $this->assertEquals('side', MenuType::SIDE->value);
        $this->assertEquals('mobile', MenuType::MOBILE->value);
        $this->assertEquals('topbar', MenuType::TOPBAR->value);
    }

    public function test_menu_type_enum_methods()
    {
        $this->assertEquals('頂部導覽', MenuType::HEADER->label());
        $this->assertEquals('fas fa-bars', MenuType::HEADER->icon());
        $this->assertTrue(MenuType::HEADER->isPrimary());
        $this->assertFalse(MenuType::FOOTER->isPrimary());
        $this->assertTrue(MenuType::MOBILE->isResponsive());
    }

    public function test_menu_type_static_methods()
    {
        $values = MenuType::values();
        $this->assertContains('header', $values);
        $this->assertContains('footer', $values);
        $this->assertCount(5, $values);

        $options = MenuType::options();
        $this->assertArrayHasKey('header', $options);
        $this->assertEquals('頂部導覽', $options['header']);
    }

    public function test_menu_style_enum_values()
    {
        $this->assertEquals('mega-menu', MenuStyle::MEGA_MENU->value);
        $this->assertEquals('single-column', MenuStyle::SINGLE_COLUMN->value);
        $this->assertEquals('multi-level', MenuStyle::MULTI_LEVEL->value);
        $this->assertEquals('simple', MenuStyle::SIMPLE->value);
    }

    public function test_menu_style_enum_methods()
    {
        $this->assertEquals('Mega Menu', MenuStyle::MEGA_MENU->label());
        $this->assertEquals('大型多欄選單', MenuStyle::MEGA_MENU->labelChinese());
        $this->assertEquals('mega-menu', MenuStyle::MEGA_MENU->cssClass());
        $this->assertEquals('sub-menu mega-sub-menu', MenuStyle::MEGA_MENU->submenuCssClass());

        $this->assertTrue(MenuStyle::MEGA_MENU->supportsColumns());
        $this->assertTrue(MenuStyle::MEGA_MENU->supportsImages());
        $this->assertFalse(MenuStyle::SIMPLE->supportsColumns());

        $this->assertEquals([3, 4, 5], MenuStyle::MEGA_MENU->getColumnRange());
        $this->assertEquals(4, MenuStyle::MEGA_MENU->getDefaultColumns());
    }

    public function test_link_type_enum_values()
    {
        $this->assertEquals('internal', LinkType::INTERNAL->value);
        $this->assertEquals('external', LinkType::EXTERNAL->value);
    }

    public function test_link_type_enum_methods()
    {
        $this->assertEquals('內部連結', LinkType::INTERNAL->label());
        $this->assertEquals('外部連結', LinkType::EXTERNAL->label());

        $this->assertFalse(LinkType::INTERNAL->shouldOpenNewTab());
        $this->assertTrue(LinkType::EXTERNAL->shouldOpenNewTab());

        $this->assertEquals('_self', LinkType::INTERNAL->defaultTarget());
        $this->assertEquals('_blank', LinkType::EXTERNAL->defaultTarget());
    }

    public function test_link_type_url_validation()
    {
        // 內部連結驗證
        $this->assertTrue(LinkType::INTERNAL->validateUrl('/about'));
        $this->assertTrue(LinkType::INTERNAL->validateUrl('#section'));
        $this->assertTrue(LinkType::INTERNAL->validateUrl('home'));
        $this->assertFalse(LinkType::INTERNAL->validateUrl(''));

        // 外部連結驗證
        $this->assertTrue(LinkType::EXTERNAL->validateUrl('https://example.com'));
        $this->assertTrue(LinkType::EXTERNAL->validateUrl('http://example.com'));
        $this->assertFalse(LinkType::EXTERNAL->validateUrl('invalid-url'));
    }

    public function test_link_type_url_formatting()
    {
        // 外部連結格式化
        $this->assertEquals('https://example.com', LinkType::EXTERNAL->formatUrl('example.com'));
        $this->assertEquals('https://example.com', LinkType::EXTERNAL->formatUrl('https://example.com'));

        // 內部連結格式化
        $this->assertEquals('/about', LinkType::INTERNAL->formatUrl('/about'));
        $this->assertEquals('#section', LinkType::INTERNAL->formatUrl('#section'));
    }

    public function test_enum_serialization()
    {
        // 測試 enum 可以正確序列化為字串
        $type = MenuType::HEADER;
        $style = MenuStyle::MEGA_MENU;
        $linkType = LinkType::INTERNAL;

        $this->assertEquals('header', (string) $type->value);
        $this->assertEquals('mega-menu', (string) $style->value);
        $this->assertEquals('internal', (string) $linkType->value);
    }

    public function test_enum_from_string()
    {
        // 測試可以從字串建立 enum
        $type = MenuType::from('header');
        $style = MenuStyle::from('mega-menu');
        $linkType = LinkType::from('internal');

        $this->assertEquals(MenuType::HEADER, $type);
        $this->assertEquals(MenuStyle::MEGA_MENU, $style);
        $this->assertEquals(LinkType::INTERNAL, $linkType);
    }

    public function test_enum_try_from_string()
    {
        // 測試安全的從字串建立 enum
        $validType = MenuType::tryFrom('header');
        $invalidType = MenuType::tryFrom('invalid');

        $this->assertEquals(MenuType::HEADER, $validType);
        $this->assertNull($invalidType);
    }
}

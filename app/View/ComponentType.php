<?php

declare(strict_types=1);

namespace App\View;

abstract class ComponentType
{
    //最外圍元件
    public const HEADER_WRAPPER = 'header_wrapper';

    //最外圍元件
    public const FOOTER_WRAPPER = 'footer_wrapper';

    public const LAYOUT_OVERLAY = 'layout_overlay';

    //部分
    public const SECTION = 'section';

    //列
    public const ROW = 'row';

    //模塊，最基礎的html元件
    public const MODULE = 'module';

    public static function isHeaderWrapper(string $component_type)
    {
        return $component_type === self::HEADER_WRAPPER;
    }

    public static function isFooterWrapper(string $component_type)
    {
        return $component_type === self::FOOTER_WRAPPER;
    }
}

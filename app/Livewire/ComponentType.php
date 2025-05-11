<?php

namespace App\Livewire;

abstract class ComponentType
{
    //最外圍元件
    const HEADER_WRAPPER = 'header_wrapper';

    //最外圍元件
    const FOOTER_WRAPPER = 'footer_wrapper';

    const LAYOUT_OVERLAY = 'layout_overlay';

    //部分
    const SECTION = 'section';

    //列
    const ROW = 'row';

    //模塊，最基礎的html元件
    const MODULE = 'module';

    static public function isHeaderWrapper(string $component_type)
    {
        return $component_type === self::HEADER_WRAPPER;
    }

    static public function isFooterWrapper(string $component_type)
    {
        return $component_type === self::FOOTER_WRAPPER;
    }
}

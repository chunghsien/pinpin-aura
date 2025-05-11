<?php

namespace App\Livewire;

use Livewire\Component;

abstract class BaseComponent extends Component
{
    /**
     * 元件的預設屬性若初始化都沒帶進去，mount會依照設定帶值
     *
     * @return array
     */
    abstract protected function getDefaultProperties(): array;

    abstract public function getConponentType(): string;

    final public function __construct()
    {
        $this->injection();
    }

    /**
     * 禁用依賴注入改用繼承類別override該函式
     *
     * @return void
     */
    protected function injection(): void {}
}

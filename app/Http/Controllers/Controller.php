<?php

namespace App\Http\Controllers;

abstract class Controller
{
    abstract public function query();

    public function post()
    {
        return abort(417, "如需使用請在繼承類別定義");
    }
}

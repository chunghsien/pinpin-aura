<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;

class IndexController extends BaseController
{
    public function query($lang)
    {
        // 回傳前端 React 的入口頁面，例如 resources/views/admin/app.blade.php
        return view('core-ui-admin::app', ['lang' => $lang]);
    }
}

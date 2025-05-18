<?php

namespace App\Http\Controllers;


class IndexController extends Controller
{
    public function query()
    {
        return view('index', [
            'layout' => $this->getLayout(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;


class IndexController extends Controller
{
    public function query()
    {
        return view('index', $this->getLayout());
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomController extends Controller
{
    public function query()
    {
        return view('custom-page', $this->getLayout());
    }
}

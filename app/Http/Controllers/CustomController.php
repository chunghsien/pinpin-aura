<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomController extends Controller
{
    public function query()
    {
        //$slug = request('customSlug');
        return view('custom-page', []);
    }
}

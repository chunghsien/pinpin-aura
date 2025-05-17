<?php

namespace App\Http\Controllers;

use App\Helpers\ViteHelper;
use App\Models\InstalledTheme;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Vite;

class IndexController extends Controller
{
    public function query()
    {
        $installedTheme = InstalledTheme
            ::where('use_type', '=', 'site')
            ->where('is_active', '=', 1)->firstOrFail();
        return view('index', [
            'layout' => $installedTheme->slug . "::layouts.app",
        ]);
    }
}

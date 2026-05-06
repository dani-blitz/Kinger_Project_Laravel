<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function switch($theme)
    {
        if (in_array($theme, ['horror', 'neon'])) {
            session(['theme' => $theme]);
        }

        return back();
    }
}

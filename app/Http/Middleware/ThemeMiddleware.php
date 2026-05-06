<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ThemeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $theme = session('theme', 'horror');
        view()->share('theme', $theme);

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModeratorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isModerator()) {
            abort(403, 'Доступ запрещён. Только для модераторов.');
        }

        return $next($request);
    }
}

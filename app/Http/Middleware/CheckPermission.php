<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!auth()->check()) {
            abort(403, 'Не авторизован');
        }

        if (!auth()->user()->canDo($permission)) {
            abort(403, 'У вас нет прав для выполнения этого действия');
        }

        return $next($request);
    }
}

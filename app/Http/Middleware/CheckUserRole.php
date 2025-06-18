<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('filament/*')) {
            return $next($request);
        }

        if (Auth::check() && Auth::user()->role === 'user') {
            return redirect('/');
        }

        return $next($request);
    }
}

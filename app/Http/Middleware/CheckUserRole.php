<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle(Request $request, Closure $next)
    {
        // Controleer of de gebruiker een 'USER' is
        if (Auth::check() && Auth::user()->role === 'user') {
            // Redirect 'USER' naar een andere pagina
            return redirect('/');
        }

        return $next($request);
    }
}

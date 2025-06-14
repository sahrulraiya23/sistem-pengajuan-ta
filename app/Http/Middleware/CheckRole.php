<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            return redirect('/')->with('error', 'Anda tidak memiliki akses untuk fitur ini');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // tambahkan ini

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 1) {
            return $next($request);
        }
        return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
    }
}

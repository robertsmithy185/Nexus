<?php

// app/Http/Middleware/AdminMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (session('role') === 'admin') {
            return $next($request);
        }

        return redirect('login')->withErrors(['access_denied' => 'Pastikan Anda Login Terlebih dahulu.']);
    }
}


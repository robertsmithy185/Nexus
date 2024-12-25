<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PengurusMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (session('role') === 'pengurus') {
            return $next($request);
        }

        return redirect('login')->withErrors(['access_denied' => 'Pastikan Anda Login Terlebih dahulu.']);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MahasiswaMiddlewere
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (session('role') === 'mahasiswa') {
            return $next($request);
        }

        return redirect('login')->withErrors(['access_denied' => 'Pastikan anda Login sebagai Mahasiswa']);
    }
}

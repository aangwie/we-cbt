<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SiswaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('siswa_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}

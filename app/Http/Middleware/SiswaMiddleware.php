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

        $siswa = \App\Models\Siswa::find(session('siswa_id'));
        if (!$siswa || !$siswa->is_logged_in) {
            session()->flush();
            return redirect()->route('login')->with('error', 'Sesi Anda telah dihentikan oleh admin. Silakan login kembali.');
        }

        return $next($request);
    }
}

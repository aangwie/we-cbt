<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route(auth()->user()->role . '.dashboard');
        }
        if (session()->has('siswa_id')) {
            return redirect()->route('siswa.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $type = $request->input('login_type');

        if ($type === 'siswa') {
            return $this->loginSiswa($request);
        }

        return $this->loginUser($request);
    }

    protected function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            $role = auth()->user()->role;
            return redirect()->route($role . '.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    protected function loginSiswa(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string',
            'password' => 'required',
        ]);

        $siswa = Siswa::where('nisn', $request->nisn)->first();

        if (!$siswa || $siswa->tanggal_lahir->format('dmY') !== $request->password) {
            return back()->withErrors(['nisn' => 'NISN atau password salah.'])->withInput($request->only('nisn', 'login_type'));
        }

        // Check if siswa already has an active login session
        if ($siswa->is_logged_in) {
            return back()->withErrors(['nisn' => 'Siswa Masih Aktif, Mohon Logout Terlebih Dahulu'])->withInput($request->only('nisn', 'login_type'));
        }

        // Mark as logged in
        $siswa->update(['is_logged_in' => true]);

        session([
            'siswa_id' => $siswa->id,
            'siswa_name' => $siswa->name,
            'siswa_nisn' => $siswa->nisn,
            'siswa_kelas' => $siswa->kelas,
        ]);

        return redirect()->route('siswa.dashboard');
    }

    public function logout(Request $request)
    {
        // Clear siswa login flag before session is destroyed
        if (session()->has('siswa_id')) {
            Siswa::where('id', session('siswa_id'))->update(['is_logged_in' => false]);
        }

        if (Auth::check()) {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

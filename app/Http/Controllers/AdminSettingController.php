<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AdminSettingController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrCreate(['id' => 1], [
            'app_name' => 'WeTest CBT',
            'app_logo' => null,
            'github_token' => null,
        ]);

        // Read the last 100 lines of laravel.log if it exists
        $logPath = storage_path('logs/laravel.log');
        $terminalLog = 'Log file not found.';
        if (File::exists($logPath)) {
            $logContent = file($logPath);
            // Get last 100 lines
            $lastLines = array_slice($logContent, -100);
            $terminalLog = implode("", $lastLines);
        }

        return view('admin.settings.index', compact('setting', 'terminalLog'));
    }

    public function update(Request $request)
    {
        $setting = Setting::firstOrCreate(['id' => 1]);

        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,ico|max:500',
            'github_token' => ['nullable', 'string', 'regex:/^ghp_[a-zA-Z0-9]{36}$/'],
        ], [
            'github_token.regex' => 'Format GitHub Token tidak valid. Harus dimulai dengan ghp_ dan diikuti 36 karakter alfanumerik.',
        ]);

        $data = [
            'app_name' => $request->app_name,
            'github_token' => $request->github_token,
        ];

        if ($request->hasFile('app_logo')) {
            if ($setting->app_logo) {
                Storage::disk('public')->delete($setting->app_logo);
            }
            $data['app_logo'] = $request->file('app_logo')->store('settings', 'public');
        }

        $setting->update($data);

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        return redirect()->route('admin.settings.index')->with('success', 'Cache berhasil dibersihkan.');
    }

    public function clearConfig()
    {
        Artisan::call('config:clear');
        return redirect()->route('admin.settings.index')->with('success', 'Config berhasil dibersihkan.');
    }
}

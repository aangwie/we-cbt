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

        // Read the last 100 lines of system_updates.log if it exists
        $logPath = storage_path('logs/system_updates.log');
        $terminalLog = "Belum ada log aktivitas sistem.\nLakukan Update CBT, Clear Cache, atau Clear Config untuk melihat log di sini.";
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

        $validatedData = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|max:1024', // Changed max size and removed mimes for simplicity as per instruction
            'github_token' => ['nullable', 'string', 'regex:/^ghp_[a-zA-Z0-9]{36}$/'],
            'date_format' => 'required|string', // Added date_format validation
        ], [
            'github_token.regex' => 'Format GitHub Token tidak valid. Harus dimulai dengan ghp_ dan diikuti 36 karakter alfanumerik.',
        ]);

        $data = [
            'app_name' => $validatedData['app_name'],
            'github_token' => $validatedData['github_token'],
            'date_format' => $validatedData['date_format'], // Include date_format
        ];

        if ($request->hasFile('app_logo')) {
            if ($setting->app_logo) {
                Storage::disk('public')->delete($setting->app_logo);
            }
            $data['app_logo'] = $request->file('app_logo')->store('settings', 'public');
        }

        // Clean up formatting - ensure github_token is null if empty string
        if (!isset($data['github_token']) || $data['github_token'] === '') {
            $data['github_token'] = null;
        }

        $setting->update($data);

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function updateSystem()
    {
        try {
            $basePath = base_path();
            $setting = Setting::first();
            $repoUrl = "https://github.com/aangwie/we-cbt.git";
            
            if (!empty($setting->github_token)) {
                // Gunakan Token untuk repo private / menghindari limit
                $repoUrl = "https://oauth2:{$setting->github_token}@github.com/aangwie/we-cbt.git";
            }
            
            $output = [];
            $status = 0;
            
            // Atasi masalah dubious ownership issue pada Git di cPanel/Shared Hosting
            exec("git config --global --add safe.directory " . escapeshellarg($basePath) . " 2>&1");
            
            $cmdList = [
                "cd " . escapeshellarg($basePath)
            ];
            
            if (!is_dir($basePath . '/.git')) {
                // Inisialisasi Git jika web di-deploy via zip (bukan clone)
                $cmdList[] = "git init";
                $cmdList[] = "git remote add origin " . escapeshellarg($repoUrl);
            } else {
                // Perbarui URL origin untuk memastikan token yang terbaru dipakai
                $cmdList[] = "git remote set-url origin " . escapeshellarg($repoUrl);
            }
            
            // Fetch, set branch ke main, reset hard agar sama persis origin, lalu migrate
            $cmdList[] = "git fetch origin";
            $cmdList[] = "git branch -M main";
            $cmdList[] = "git reset --hard origin/main";
            $cmdList[] = "php artisan migrate --force";
            
            // Jalankan rantai perintah
            $fullCmd = implode(" 2>&1 && ", $cmdList) . " 2>&1";
            exec($fullCmd, $output, $status);
            
            $log = implode("\n", $output);
            
            // Sembunyikan token pada log agar aman
            if (!empty($setting->github_token)) {
                $log = str_replace($setting->github_token, '***TOKEN_HIDDEN***', $log);
            }
            
            $this->logSystemAction('Update CBT (GitHub Sync)', $log);
            
            if ($status !== 0) {
                return back()->withErrors(['update' => 'Gagal menarik pembaruan: ' . $log]);
            }
            
            // Clear caches after update
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            
            return back()->with('success', "Sistem berhasil disinkronisasi dari GitHub dan dimigrasi.\nSilakan periksa log terminal untuk detail.");
        } catch (\Exception $e) {
            $this->logSystemAction('Update CBT (Error)', $e->getMessage());
            return back()->withErrors(['update' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    public function clearCache()
    {
        $log = "";
        Artisan::call('cache:clear');
        $log .= Artisan::output();
        Artisan::call('view:clear');
        $log .= Artisan::output();
        Artisan::call('route:clear');
        $log .= Artisan::output();
        
        $this->logSystemAction('Clear Cache', trim($log));
        
        return redirect()->route('admin.settings.index')->with('success', 'Cache berhasil dibersihkan.');
    }

    public function clearConfig()
    {
        Artisan::call('config:clear');
        $log = Artisan::output();
        
        $this->logSystemAction('Clear Config', trim($log));
        
        return redirect()->route('admin.settings.index')->with('success', 'Config berhasil dibersihkan.');
    }

    public function linkStorage()
    {
        try {
            Artisan::call('storage:link');
            $log = Artisan::output();
            
            // If artisan output is empty but successful, provide a default success message
            if (empty(trim($log))) {
                $log = "The [public/storage] link has been connected to [storage/app/public].";
            }
            
            $this->logSystemAction('Link Storage', trim($log));
            
            return redirect()->route('admin.settings.index')->with('success', 'Storage link berhasil dibuat. ' . $log);
        } catch (\Exception $e) {
            $this->logSystemAction('Link Storage (Error)', $e->getMessage());
            
            // Fallback for shared hosting if Artisan fails
            try {
                $target = storage_path('app/public');
                $link = public_path('storage');
                
                if (file_exists($link)) {
                    return redirect()->route('admin.settings.index')->withErrors(['update' => 'Symlink sudah ada atau tidak bisa ditimpa.']);
                }
                
                symlink($target, $link);
                $this->logSystemAction('Link Storage (Fallback)', 'Symlink created manually via symlink() function.');
                return redirect()->route('admin.settings.index')->with('success', 'Storage link berhasil dibuat (Metode Fallback).');
            } catch (\Exception $fallbackE) {
                return redirect()->route('admin.settings.index')->withErrors(['update' => 'Gagal membuat symlink: ' . $e->getMessage() . ' | Fallback error: ' . $fallbackE->getMessage()]);
            }
        }
    }

    private function logSystemAction($action, $output)
    {
        $logPath = storage_path('logs/system_updates.log');
        $timestamp = now()->format('Y-m-d H:i:s');
        $logEntry = "[$timestamp] ACTION: $action\n" . $output . "\n" . str_repeat("-", 50) . "\n";
        File::append($logPath, $logEntry);
    }

    // ─── SEB (Safe Exam Browser) Settings ───
    public function sebIndex()
    {
        $setting = Setting::firstOrCreate(['id' => 1], [
            'app_name' => 'WeTest CBT',
        ]);

        return view('admin.settings.seb', compact('setting'));
    }

    public function sebUpdate(Request $request)
    {
        $setting = Setting::firstOrCreate(['id' => 1]);

        $validated = $request->validate([
            'seb_enabled' => 'nullable|boolean',
            'seb_key' => ['nullable', 'string', 'regex:/^[a-fA-F0-9]{64}$/'],
        ], [
            'seb_key.regex' => 'Browser Exam Key harus berupa hash SHA-256 (64 karakter hexadecimal).',
        ]);

        $setting->update([
            'seb_enabled' => $request->boolean('seb_enabled'),
            'seb_key' => $validated['seb_key'] ?: null,
        ]);

        return back()->with('success', 'Pengaturan SEB berhasil diperbarui.');
    }
}

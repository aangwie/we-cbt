<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class VerifySEB
{
    /**
     * Verify that the request is coming from Safe Exam Browser
     * by checking the X-SafeExamBrowser-RequestHash header against
     * the stored Browser Exam Key.
     *
     * SEB sends a hash of: URL + BEK (SHA-256)
     * Header: X-SafeExamBrowser-RequestHash
     */
    public function handle(Request $request, Closure $next): Response
    {
        $setting = Setting::first();

        // If SEB is not enabled or no key is configured, allow all requests
        if (!$setting || !$setting->seb_enabled || empty($setting->seb_key)) {
            return $next($request);
        }

        // Check for SEB request hash header
        $sebHash = $request->header('X-SafeExamBrowser-RequestHash');

        if (empty($sebHash)) {
            // No SEB header found — not using Safe Exam Browser
            return $this->blocked($request);
        }

        // Validate: SEB generates hash = sha256(url + BEK)
        $currentUrl = $request->fullUrl();
        $expectedHash = hash('sha256', $currentUrl . $setting->seb_key);

        if (!hash_equals($expectedHash, $sebHash)) {
            return $this->blocked($request);
        }

        return $next($request);
    }

    /**
     * Return a blocked response for non-SEB browsers
     */
    protected function blocked(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Akses ditolak. Ujian hanya dapat diakses melalui Safe Exam Browser (SEB).',
            ], 403);
        }

        return response()->view('siswa.seb_blocked', [], 403);
    }
}

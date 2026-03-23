<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\HasilUjian;
use App\Models\JawabanSementara;
use App\Models\SesiUjian;
use Carbon\Carbon;

class SiswaController extends Controller
{
    protected function getSiswa()
    {
        return Siswa::findOrFail(session('siswa_id'));
    }

    // ─── Dashboard ───
    public function dashboard()
    {
        $siswa = $this->getSiswa();
        $hasilUjians = HasilUjian::where('siswa_id', $siswa->id)
            ->with('ujian')
            ->latest()
            ->get();

        $finishedUjianIds = $hasilUjians->pluck('ujian_id')->toArray();

        $activeSessions = collect();

        // Source 1: From sesi_ujians table (new sessions)
        $sesiRecords = SesiUjian::where('siswa_id', $siswa->id)
            ->whereNotIn('ujian_id', $finishedUjianIds)
            ->with('ujian')
            ->get();

        foreach ($sesiRecords as $sesi) {
            $endTime = $sesi->started_at->timestamp + ($sesi->ujian->durasi * 60);
            $rem = max(0, $endTime - now()->timestamp);
            $activeSessions->push((object)[
                'ujian'             => $sesi->ujian,
                'ujian_id'          => $sesi->ujian_id,
                'remaining_seconds' => $rem,
                'is_expired'        => $rem <= 0,
                'answered_count'    => JawabanSementara::where('siswa_id', $siswa->id)->where('ujian_id', $sesi->ujian_id)->count(),
                'total_soal'        => $sesi->ujian->soals()->count(),
            ]);
        }

        // Source 2: From jawaban_sementaras without sesi_ujian (legacy/fallback)
        $coveredUjianIds = array_merge($finishedUjianIds, $activeSessions->pluck('ujian_id')->toArray());
        $legacyUjianIds = JawabanSementara::where('siswa_id', $siswa->id)
            ->whereNotIn('ujian_id', $coveredUjianIds)
            ->distinct()
            ->pluck('ujian_id')
            ->toArray();

        if (!empty($legacyUjianIds)) {
            $ujians = Ujian::whereIn('id', $legacyUjianIds)->get();
            foreach ($ujians as $ujian) {
                $activeSessions->push((object)[
                    'ujian'             => $ujian,
                    'ujian_id'          => $ujian->id,
                    'remaining_seconds' => 0,
                    'is_expired'        => true,
                    'answered_count'    => JawabanSementara::where('siswa_id', $siswa->id)->where('ujian_id', $ujian->id)->count(),
                    'total_soal'        => $ujian->soals()->count(),
                ]);
            }
        }

        return view('siswa.dashboard', compact('siswa', 'hasilUjians', 'activeSessions'));
    }

    // ─── Pre-exam confirmation page ───
    public function konfirmasi()
    {
        $siswa = $this->getSiswa();
        return view('siswa.konfirmasi', compact('siswa'));
    }

    // ─── Validate token and start/resume exam ───
    public function validateToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $siswa = $this->getSiswa();

        $ujian = Ujian::where('token', strtoupper($request->token))
            ->where('is_active', true)
            ->first();

        if (!$ujian) {
            return back()->withErrors(['token' => 'Token tidak valid atau ujian tidak aktif.'])->withInput();
        }

        // Check if already completed this exam
        $exists = HasilUjian::where('siswa_id', $siswa->id)
            ->where('ujian_id', $ujian->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['token' => 'Anda sudah mengerjakan ujian ini.'])->withInput();
        }

        // Check for existing session (resume)
        $sesi = SesiUjian::where('siswa_id', $siswa->id)
            ->where('ujian_id', $ujian->id)
            ->first();

        if ($sesi) {
            // Resume: check if time hasn't expired
            $endTime = $sesi->started_at->timestamp + ($ujian->durasi * 60);
            $remaining = $endTime - now()->timestamp;

            if ($remaining <= 0) {
                // Time expired while away — auto-submit with saved answers
                $this->autoSubmitExpired($siswa, $ujian, $sesi);
                return back()->withErrors(['token' => 'Waktu ujian sudah habis. Jawaban yang tersimpan telah dikumpulkan otomatis.'])->withInput();
            }

            // Resume session
            session([
                'ujian_id'    => $ujian->id,
                'ujian_start' => $sesi->started_at->timestamp,
            ]);

            return redirect()->route('siswa.ujian', $ujian->id);
        }

        // New exam session — create with shuffled question order
        $soalIds = $ujian->soals()->pluck('id')->toArray();
        shuffle($soalIds);

        SesiUjian::create([
            'siswa_id'   => $siswa->id,
            'ujian_id'   => $ujian->id,
            'started_at' => now(),
            'soal_order' => $soalIds,
        ]);

        session([
            'ujian_id'    => $ujian->id,
            'ujian_start' => now()->timestamp,
        ]);

        return redirect()->route('siswa.ujian', $ujian->id);
    }

    // ─── Auto-submit for expired sessions ───
    protected function autoSubmitExpired(Siswa $siswa, Ujian $ujian, SesiUjian $sesi)
    {
        $soals = $ujian->soals;
        $jumlahBenar = 0;

        $dbAnswers = JawabanSementara::where('siswa_id', $siswa->id)
            ->where('ujian_id', $ujian->id)
            ->pluck('jawaban', 'soal_id')
            ->toArray();

        foreach ($soals as $soal) {
            $jawaban = $dbAnswers[$soal->id] ?? null;
            if ($jawaban && strtolower($jawaban) === $soal->jawaban_benar) {
                $jumlahBenar++;
            }
        }

        $totalSoal = $soals->count();
        $nilai = $totalSoal > 0 ? ($jumlahBenar / $totalSoal) * 100 : 0;

        HasilUjian::create([
            'siswa_id'     => $siswa->id,
            'ujian_id'     => $ujian->id,
            'jumlah_benar' => $jumlahBenar,
            'nilai'        => round($nilai, 2),
            'tgl_selesai'  => $sesi->started_at->addMinutes($ujian->durasi),
        ]);

        // Cleanup
        JawabanSementara::where('siswa_id', $siswa->id)->where('ujian_id', $ujian->id)->delete();
        $sesi->delete();
        session()->forget(['ujian_id', 'ujian_start', 'saved_answers']);
    }

    // ─── Exam page ───
    public function ujian(Ujian $ujian)
    {
        $siswa = $this->getSiswa();

        // Verify session
        if (session('ujian_id') != $ujian->id) {
            return redirect()->route('siswa.konfirmasi')->withErrors(['token' => 'Silakan masukkan token ujian terlebih dahulu.']);
        }

        // Get session for question order
        $sesi = SesiUjian::where('siswa_id', $siswa->id)
            ->where('ujian_id', $ujian->id)
            ->first();

        if (!$sesi) {
            return redirect()->route('siswa.konfirmasi')->withErrors(['token' => 'Sesi ujian tidak ditemukan.']);
        }

        // Load soals in the randomized order
        $soalOrder = $sesi->soal_order;
        $allSoals = $ujian->soals->keyBy('id');
        $soals = collect($soalOrder)->map(fn($id) => $allSoals->get($id))->filter()->values();

        // Calculate remaining time from DB start time
        $startTime = $sesi->started_at->timestamp;
        $endTime = $startTime + ($ujian->durasi * 60);
        $remainingSeconds = max(0, $endTime - now()->timestamp);

        // Load previously saved answers for resume
        $savedAnswers = JawabanSementara::where('siswa_id', $siswa->id)
            ->where('ujian_id', $ujian->id)
            ->pluck('jawaban', 'soal_id')
            ->toArray();

        return view('siswa.ujian', compact('siswa', 'ujian', 'soals', 'remainingSeconds', 'savedAnswers'));
    }

    // ─── Save individual answer (AJAX) ───
    public function saveAnswer(Request $request, Ujian $ujian)
    {
        $siswa = $this->getSiswa();

        if (session('ujian_id') != $ujian->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'soal_id' => 'required|integer',
            'jawaban' => 'required|in:a,b,c,d,e',
        ]);

        // Save to DB for admin monitoring
        JawabanSementara::updateOrCreate(
            [
                'siswa_id' => $siswa->id,
                'ujian_id' => $ujian->id,
                'soal_id'  => $request->soal_id,
            ],
            ['jawaban' => $request->jawaban]
        );

        return response()->json(['status' => 'saved']);
    }

    // ─── Submit exam ───
    public function submitUjian(Request $request, Ujian $ujian)
    {
        $siswa = $this->getSiswa();

        if (session('ujian_id') != $ujian->id) {
            return redirect()->route('siswa.konfirmasi');
        }

        $soals = $ujian->soals;
        $jumlahBenar = 0;

        $dbAnswers = JawabanSementara::where('siswa_id', $siswa->id)
            ->where('ujian_id', $ujian->id)
            ->pluck('jawaban', 'soal_id')
            ->toArray();

        foreach ($soals as $soal) {
            $jawaban = $request->input('jawaban_' . $soal->id)
                ?? ($dbAnswers[$soal->id] ?? null);
            if ($jawaban && strtolower($jawaban) === $soal->jawaban_benar) {
                $jumlahBenar++;
            }
        }

        $totalSoal = $soals->count();
        $nilai = $totalSoal > 0 ? ($jumlahBenar / $totalSoal) * 100 : 0;

        $hasil = HasilUjian::create([
            'siswa_id'     => $siswa->id,
            'ujian_id'     => $ujian->id,
            'jumlah_benar' => $jumlahBenar,
            'nilai'        => round($nilai, 2),
            'tgl_selesai'  => now(),
        ]);

        // Cleanup
        JawabanSementara::where('siswa_id', $siswa->id)->where('ujian_id', $ujian->id)->delete();
        SesiUjian::where('siswa_id', $siswa->id)->where('ujian_id', $ujian->id)->delete();
        session()->forget(['ujian_id', 'ujian_start', 'saved_answers']);

        return redirect()->route('siswa.hasil', $hasil->id);
    }

    // ─── Result page ───
    public function hasil(HasilUjian $hasil)
    {
        $siswa = $this->getSiswa();

        if ($hasil->siswa_id !== $siswa->id) {
            abort(403);
        }

        $hasil->load('ujian');
        $totalSoal = $hasil->ujian->soals()->count();

        return view('siswa.hasil', compact('siswa', 'hasil', 'totalSoal'));
    }
}

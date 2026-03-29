<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Ujian;
use App\Models\HasilUjian;
use App\Models\JawabanSementara;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // ─── Dashboard ───
    public function dashboard()
    {
        $totalGuru = User::where('role', 'guru')->count();
        $totalSiswa = Siswa::count();
        $totalUjian = Ujian::count();
        $totalHasil = HasilUjian::count();
        $recentUjians = Ujian::with('guru')->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalGuru', 'totalSiswa', 'totalUjian', 'totalHasil', 'recentUjians'));
    }

    // ─── Guru CRUD ───
    public function guruIndex()
    {
        $gurus = User::where('role', 'guru')->with('mapels')->latest()->get();
        return view('admin.guru.index', compact('gurus'));
    }

    public function guruCreate()
    {
        $mapels = \App\Models\Mapel::orderBy('nama_mapel')->get();
        return view('admin.guru.create', compact('mapels'));
    }

    public function guruStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'mapels' => 'nullable|array',
            'mapels.*' => 'exists:mapels,id',
        ]);

        $guru = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'guru',
        ]);

        if ($request->has('mapels')) {
            $guru->mapels()->sync($request->mapels);
        }

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function guruEdit(User $guru)
    {
        $mapels = \App\Models\Mapel::orderBy('nama_mapel')->get();
        return view('admin.guru.edit', compact('guru', 'mapels'));
    }

    public function guruUpdate(Request $request, User $guru)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $guru->id,
            'password' => 'nullable|string|min:6|confirmed',
            'mapels' => 'nullable|array',
            'mapels.*' => 'exists:mapels,id',
        ]);

        $guru->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $guru->password,
        ]);

        if ($request->has('mapels')) {
            $guru->mapels()->sync($request->mapels);
        } else {
            $guru->mapels()->detach();
        }

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil diperbarui.');
    }

    public function guruDestroy(User $guru)
    {
        $guru->delete();
        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil dihapus.');
    }

    // ─── Siswa CRUD ───
    public function siswaIndex()
    {
        $siswas = Siswa::latest()->get();
        return view('admin.siswa.index', compact('siswas'));
    }

    public function siswaAktifIndex()
    {
        $activeSiswas = Siswa::where('is_logged_in', true)->latest()->get();
        return view('admin.siswa.aktif', compact('activeSiswas'));
    }

    public function siswaCreate()
    {
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.create', compact('kelasList'));
    }

    public function siswaStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => 'required|exists:kelas,nama_kelas',
            'nisn' => 'required|string|max:20|unique:siswas,nisn',
        ]);

        Siswa::create($request->only('name', 'tanggal_lahir', 'jenis_kelamin', 'kelas', 'nisn'));

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function siswaEdit(Siswa $siswa)
    {
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswa.edit', compact('siswa', 'kelasList'));
    }

    public function siswaUpdate(Request $request, Siswa $siswa)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => 'required|exists:kelas,nama_kelas',
            'nisn' => 'required|string|max:20|unique:siswas,nisn,' . $siswa->id,
        ]);

        $siswa->update($request->only('name', 'tanggal_lahir', 'jenis_kelamin', 'kelas', 'nisn'));

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function siswaDestroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }

    public function siswaKick(Siswa $siswa)
    {
        $siswa->update(['is_logged_in' => false]);
        return redirect()->back()->with('success', 'Siswa ' . $siswa->name . ' berhasil di-kick dari sesinya.');
    }

    public function siswaImportForm()
    {
        return view('admin.siswa.import');
    }

    public function siswaImport(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            $rows = \Maatwebsite\Excel\Facades\Excel::toCollection(new \App\Imports\SiswaImport, $request->file('file_excel'))->first() ?? collect();

            $success = 0;
            $failed = 0;
            $errors = [];
            
            $existingNisn = \App\Models\Siswa::pluck('nisn')->toArray();
            $existingKelasMap = \App\Models\Kelas::pluck('nama_kelas')->mapWithKeys(function ($name) {
                return [strtolower(trim($name)) => trim($name)];
            })->toArray();

            foreach ($rows as $index => $row) {
                // Ensure row is not completely empty
                if (!isset($row['nama']) && !isset($row['nisn']) && !isset($row['kelas'])) continue;

                $nama = isset($row['nama']) ? trim($row['nama']) : null;
                $nisn = isset($row['nisn']) ? trim($row['nisn']) : null;
                $kelasRaw = isset($row['kelas']) ? trim($row['kelas']) : null;
                $kelasKey = $kelasRaw ? strtolower($kelasRaw) : null;
                $jk = strtoupper(trim($row['jenis_kelamin'] ?? ''));
                $tgllahir = isset($row['tanggal_lahir']) ? trim($row['tanggal_lahir']) : null;

                if (!$nama || !$nisn || !$kelasRaw || !in_array($jk, ['L', 'P']) || !$tgllahir) {
                    $failed++;
                    $errors[] = "Baris " . ($index + 2) . ": Data tidak lengkap atau format (L/P) salah.";
                    continue;
                }

                if (in_array($nisn, $existingNisn)) {
                    $failed++;
                    $errors[] = "Baris " . ($index + 2) . ": NISN {$nisn} sudah terdaftar.";
                    continue;
                }

                if (!isset($existingKelasMap[$kelasKey])) {
                    $failed++;
                    $errors[] = "Baris " . ($index + 2) . ": Kelas '{$kelasRaw}' belum terdaftar di menu Kelola Kelas.";
                    continue;
                }
                
                $kelas = $existingKelasMap[$kelasKey];

                if (is_numeric($tgllahir)) {
                    $tgllahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tgllahir)->format('Y-m-d');
                } else {
                    $tgllahir = date('Y-m-d', strtotime($tgllahir));
                }

                \App\Models\Siswa::create([
                    'name' => $nama,
                    'nisn' => $nisn,
                    'kelas' => $kelas,
                    'jenis_kelamin' => $jk,
                    'tanggal_lahir' => $tgllahir,
                ]);

                $existingNisn[] = $nisn;
                $success++;
            }

            return response()->json([
                'status' => 'success',
                'success_count' => $success,
                'failed_count' => $failed,
                'errors' => array_slice($errors, 0, 10) // return up to 10 errors to avoid huge payload
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat import: ' . $e->getMessage()
            ], 500);
        }
    }

    public function siswaTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SiswaTemplateExport, 'template_siswa.xlsx');
    }

    // ─── Ujian CRUD (Admin only) ───
    public function ujianIndex()
    {
        $ujians = Ujian::with(['guru', 'mapel'])->withCount('soals')->latest()->get();
        return view('admin.ujian.index', compact('ujians'));
    }

    public function ujianCreate()
    {
        $gurus = User::where('role', 'guru')->get();
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();
        $mapels = \App\Models\Mapel::orderBy('nama_mapel')->get();
        $paketSoals = \App\Models\PaketSoal::has('soals')->withCount('soals')->with('mapel')->orderBy('judul')->get();
        return view('admin.ujian.create', compact('gurus', 'kelasList', 'mapels', 'paketSoals'));
    }

    public function ujianStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'mapel_id' => 'required|exists:mapels,id',
            'guru_id' => 'required|exists:users,id',
            'paket_soal_id' => 'required|exists:paket_soals,id',
            'durasi' => 'required|integer|min:1',
            'kelas' => 'nullable|exists:kelas,nama_kelas',
        ]);

        Ujian::create([
            'judul' => $request->judul,
            'mapel_id' => $request->mapel_id,
            'guru_id' => $request->guru_id,
            'paket_soal_id' => $request->paket_soal_id,
            'token' => strtoupper(Str::random(6)),
            'is_active' => $request->boolean('is_active'),
            'durasi' => $request->durasi,
            'kelas' => $request->kelas,
        ]);

        return redirect()->route('admin.ujian.index')->with('success', 'Ujian berhasil dibuat.');
    }

    public function ujianShow(Ujian $ujian)
    {
        $ujian->load(['guru', 'soals', 'hasilUjians.siswa']);
        $totalSoal = $ujian->soals->count();

        // Get students currently taking the exam (have temp answers but no result yet)
        $finishedSiswaIds = $ujian->hasilUjians->pluck('siswa_id')->toArray();

        $activeSiswa = JawabanSementara::where('ujian_id', $ujian->id)
            ->whereNotIn('siswa_id', $finishedSiswaIds)
            ->selectRaw('siswa_id, COUNT(*) as answered_count, MAX(updated_at) as last_activity')
            ->groupBy('siswa_id')
            ->with('siswa')
            ->get();

        return view('admin.ujian.show', compact('ujian', 'totalSoal', 'activeSiswa'));
    }

    public function ujianEdit(Ujian $ujian)
    {
        $gurus = User::where('role', 'guru')->get();
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();
        $mapels = \App\Models\Mapel::orderBy('nama_mapel')->get();
        $paketSoals = \App\Models\PaketSoal::has('soals')->withCount('soals')->with('mapel')->orderBy('judul')->get();
        return view('admin.ujian.edit', compact('ujian', 'gurus', 'kelasList', 'mapels', 'paketSoals'));
    }

    public function ujianUpdate(Request $request, Ujian $ujian)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'mapel_id' => 'required|exists:mapels,id',
            'guru_id' => 'required|exists:users,id',
            'paket_soal_id' => 'required|exists:paket_soals,id',
            'durasi' => 'required|integer|min:1',
            'kelas' => 'nullable|exists:kelas,nama_kelas',
        ]);

        $ujian->update([
            'judul' => $request->judul,
            'mapel_id' => $request->mapel_id,
            'guru_id' => $request->guru_id,
            'paket_soal_id' => $request->paket_soal_id,
            'is_active' => $request->boolean('is_active'),
            'durasi' => $request->durasi,
            'kelas' => $request->kelas,
        ]);

        return redirect()->route('admin.ujian.index')->with('success', 'Ujian berhasil diperbarui.');
    }

    public function ujianRegenerateToken(Ujian $ujian)
    {
        $ujian->update(['token' => strtoupper(Str::random(6))]);
        return redirect()->back()->with('success', 'Token berhasil di-generate ulang: ' . $ujian->token);
    }
    public function ujianResetPeserta(Ujian $ujian, Siswa $siswa)
    {
        // Clear login flag only, so they can login again but keep their answers/session
        $siswa->update(['is_logged_in' => false]);

        return redirect()->back()->with('success', 'Sesi login peserta ' . $siswa->name . ' berhasil di-reset. Jawaban tetap aman.');
    }

    public function ujianDestroy(Ujian $ujian)
    {
        $ujian->delete();
        return redirect()->route('admin.ujian.index')->with('success', 'Ujian berhasil dihapus.');
    }
}

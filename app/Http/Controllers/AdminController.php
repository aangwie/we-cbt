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
        $gurus = User::where('role', 'guru')->latest()->get();
        return view('admin.guru.index', compact('gurus'));
    }

    public function guruCreate()
    {
        return view('admin.guru.create');
    }

    public function guruStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'guru',
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function guruEdit(User $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function guruUpdate(Request $request, User $guru)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $guru->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $guru->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $guru->password,
        ]);

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

    public function siswaCreate()
    {
        return view('admin.siswa.create');
    }

    public function siswaStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => 'required|string|max:50',
            'nisn' => 'required|string|max:20|unique:siswas,nisn',
        ]);

        Siswa::create($request->only('name', 'tanggal_lahir', 'jenis_kelamin', 'kelas', 'nisn'));

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function siswaEdit(Siswa $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function siswaUpdate(Request $request, Siswa $siswa)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => 'required|string|max:50',
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

    public function siswaImport(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\SiswaImport, $request->file('file_excel'));
            return back()->with('success', 'Data siswa berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    // ─── Ujian CRUD (Admin only) ───
    public function ujianIndex()
    {
        $ujians = Ujian::with('guru')->withCount('soals')->latest()->get();
        return view('admin.ujian.index', compact('ujians'));
    }

    public function ujianCreate()
    {
        $gurus = User::where('role', 'guru')->get();
        return view('admin.ujian.create', compact('gurus'));
    }

    public function ujianStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'guru_id' => 'required|exists:users,id',
            'durasi' => 'required|integer|min:1',
            'kelas' => 'nullable|in:VII,VIII,IX',
        ]);

        Ujian::create([
            'judul' => $request->judul,
            'guru_id' => $request->guru_id,
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
        return view('admin.ujian.edit', compact('ujian', 'gurus'));
    }

    public function ujianUpdate(Request $request, Ujian $ujian)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'guru_id' => 'required|exists:users,id',
            'durasi' => 'required|integer|min:1',
            'kelas' => 'nullable|in:VII,VIII,IX',
        ]);

        $ujian->update([
            'judul' => $request->judul,
            'guru_id' => $request->guru_id,
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

    public function ujianDestroy(Ujian $ujian)
    {
        $ujian->delete();
        return redirect()->route('admin.ujian.index')->with('success', 'Ujian berhasil dihapus.');
    }
}

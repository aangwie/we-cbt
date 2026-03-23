<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\HasilUjian;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;

class GuruController extends Controller
{
    // ─── Dashboard ───
    public function dashboard()
    {
        $guru = auth()->user();
        $ujians = Ujian::where('guru_id', $guru->id)->withCount('soals')->latest()->get();
        $totalSoal = Soal::whereIn('ujian_id', $ujians->pluck('id'))->count();
        $totalHasil = HasilUjian::whereIn('ujian_id', $ujians->pluck('id'))->count();

        return view('guru.dashboard', compact('ujians', 'totalSoal', 'totalHasil'));
    }

    // ─── Soal CRUD ───
    public function soalIndex()
    {
        $guru = auth()->user();
        $ujians = Ujian::where('guru_id', $guru->id)->with('soals')->get();
        return view('guru.soal.index', compact('ujians'));
    }

    public function soalCreate()
    {
        $guru = auth()->user();
        $ujians = Ujian::where('guru_id', $guru->id)->get();
        return view('guru.soal.create', compact('ujians'));
    }

    public function soalStore(Request $request)
    {
        $guru = auth()->user();

        $request->validate([
            'ujian_id' => 'required|exists:ujians,id',
            'teks_soal' => 'required|string',
            'gambar_soal' => 'nullable|image|max:500',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'pilihan_e' => 'required|string',
            'gambar_a' => 'nullable|image|max:500',
            'gambar_b' => 'nullable|image|max:500',
            'gambar_c' => 'nullable|image|max:500',
            'gambar_d' => 'nullable|image|max:500',
            'gambar_e' => 'nullable|image|max:500',
            'jawaban_benar' => 'required|in:a,b,c,d,e',
        ]);

        // Verify the ujian belongs to this guru
        $ujian = Ujian::where('id', $request->ujian_id)->where('guru_id', $guru->id)->firstOrFail();

        $data = $request->only([
            'ujian_id', 'teks_soal',
            'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e',
            'jawaban_benar',
        ]);

        // Handle image uploads — convert to WebP
        $imageFields = ['gambar_soal', 'gambar_a', 'gambar_b', 'gambar_c', 'gambar_d', 'gambar_e'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = ImageHelper::storeAsWebp($request->file($field), 'soal');
            }
        }

        Soal::create($data);

        return redirect()->route('guru.soal.index')->with('success', 'Soal berhasil ditambahkan.');
    }

    public function soalEdit(Soal $soal)
    {
        $guru = auth()->user();
        // Verify ownership
        $ujian = Ujian::where('id', $soal->ujian_id)->where('guru_id', $guru->id)->firstOrFail();
        $ujians = Ujian::where('guru_id', $guru->id)->get();

        return view('guru.soal.edit', compact('soal', 'ujians'));
    }

    public function soalUpdate(Request $request, Soal $soal)
    {
        $guru = auth()->user();
        Ujian::where('id', $soal->ujian_id)->where('guru_id', $guru->id)->firstOrFail();

        $request->validate([
            'ujian_id' => 'required|exists:ujians,id',
            'teks_soal' => 'required|string',
            'gambar_soal' => 'nullable|image|max:500',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'pilihan_e' => 'required|string',
            'gambar_a' => 'nullable|image|max:500',
            'gambar_b' => 'nullable|image|max:500',
            'gambar_c' => 'nullable|image|max:500',
            'gambar_d' => 'nullable|image|max:500',
            'gambar_e' => 'nullable|image|max:500',
            'jawaban_benar' => 'required|in:a,b,c,d,e',
        ]);

        $data = $request->only([
            'ujian_id', 'teks_soal',
            'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e',
            'jawaban_benar',
        ]);

        $imageFields = ['gambar_soal', 'gambar_a', 'gambar_b', 'gambar_c', 'gambar_d', 'gambar_e'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old image
                if ($soal->{$field}) {
                    Storage::disk('public')->delete($soal->{$field});
                }
                $data[$field] = ImageHelper::storeAsWebp($request->file($field), 'soal');
            }
        }

        $soal->update($data);

        return redirect()->route('guru.soal.index')->with('success', 'Soal berhasil diperbarui.');
    }

    public function soalDestroy(Soal $soal)
    {
        $guru = auth()->user();
        Ujian::where('id', $soal->ujian_id)->where('guru_id', $guru->id)->firstOrFail();

        // Delete associated images
        $imageFields = ['gambar_soal', 'gambar_a', 'gambar_b', 'gambar_c', 'gambar_d', 'gambar_e'];
        foreach ($imageFields as $field) {
            if ($soal->{$field}) {
                Storage::disk('public')->delete($soal->{$field});
            }
        }

        $soal->delete();

        return redirect()->route('guru.soal.index')->with('success', 'Soal berhasil dihapus.');
    }

    // ─── Hasil Ujian (View Only) ───
    public function hasilIndex()
    {
        $guru = auth()->user();
        $ujians = Ujian::where('guru_id', $guru->id)
            ->with(['hasilUjians.siswa'])
            ->withCount('soals')
            ->get();

        return view('guru.hasil.index', compact('ujians'));
    }
}

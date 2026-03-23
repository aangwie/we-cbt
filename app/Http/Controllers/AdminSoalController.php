<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;

class AdminSoalController extends Controller
{
    public function index()
    {
        $ujians = Ujian::with('soals')->get();
        return view('admin.soal.index', compact('ujians'));
    }

    public function create()
    {
        $ujians = Ujian::all();
        return view('admin.soal.create', compact('ujians'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ujian_id' => 'required|exists:ujians,id',
            'teks_soal' => 'required|string',
            'gambar_soal' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:500',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'pilihan_e' => 'required|string',
            'gambar_a' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:500',
            'gambar_b' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:500',
            'gambar_c' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:500',
            'gambar_d' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:500',
            'gambar_e' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:500',
            'jawaban_benar' => 'required|in:a,b,c,d,e',
        ]);

        $imageFields = ['gambar_soal', 'gambar_a', 'gambar_b', 'gambar_c', 'gambar_d', 'gambar_e'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = ImageHelper::storeAsWebp($request->file($field), 'soal');
            }
        }

        Soal::create($data);

        return redirect()->route('admin.soal.index')->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(Soal $soal)
    {
        $ujians = Ujian::all();
        return view('admin.soal.edit', compact('soal', 'ujians'));
    }

    public function update(Request $request, Soal $soal)
    {
        $data = $request->validate([
            'ujian_id' => 'required|exists:ujians,id',
            'teks_soal' => 'required|string',
            'gambar_soal' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:500',
            'pilihan_a' => 'required|string',
            'pilihan_b' => 'required|string',
            'pilihan_c' => 'required|string',
            'pilihan_d' => 'required|string',
            'pilihan_e' => 'required|string',
            'gambar_a' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:500',
            'gambar_b' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:500',
            'gambar_c' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:500',
            'gambar_d' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:500',
            'gambar_e' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:500',
            'jawaban_benar' => 'required|in:a,b,c,d,e',
        ]);

        $imageFields = ['gambar_soal', 'gambar_a', 'gambar_b', 'gambar_c', 'gambar_d', 'gambar_e'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                if ($soal->$field) Storage::disk('public')->delete($soal->$field);
                $data[$field] = ImageHelper::storeAsWebp($request->file($field), 'soal');
            }
        }

        $soal->update($data);

        return redirect()->route('admin.soal.index')->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Soal $soal)
    {
        $imageFields = ['gambar_soal', 'gambar_a', 'gambar_b', 'gambar_c', 'gambar_d', 'gambar_e'];
        foreach ($imageFields as $field) {
            if ($soal->$field) Storage::disk('public')->delete($soal->$field);
        }

        $soal->delete();

        return redirect()->route('admin.soal.index')->with('success', 'Soal berhasil dihapus.');
    }
}

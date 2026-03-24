<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;

// Add required models
use App\Models\Mapel;
use App\Models\Kelas;

class AdminSoalController extends Controller
{
    public function index()
    {
        // Get grouped mapel and kelas summaries
        $summaries = \Illuminate\Support\Facades\DB::table('soals')
            ->join('mapels', 'soals.mapel_id', '=', 'mapels.id')
            ->select('mapels.id as mapel_id', 'mapels.nama_mapel', 'mapels.kode_mapel', 'soals.kelas', \Illuminate\Support\Facades\DB::raw('count(*) as total_soal'))
            ->groupBy('mapels.id', 'mapels.nama_mapel', 'mapels.kode_mapel', 'soals.kelas')
            ->orderBy('mapels.nama_mapel')
            ->orderBy('soals.kelas')
            ->get();

        return view('admin.soal.index', compact('summaries'));
    }

    public function detail(Request $request)
    {
        $mapel_id = $request->mapel_id;
        $kelas = $request->kelas;

        abort_if(!$mapel_id || !$kelas, 404);

        $mapel = Mapel::findOrFail($mapel_id);
        $soals = Soal::where('mapel_id', $mapel_id)
            ->where('kelas', $kelas)
            ->with('ujian')
            ->get();

        return view('admin.soal.detail', compact('mapel', 'kelas', 'soals'));
    }

    public function create()
    {
        $ujians = Ujian::all();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('admin.soal.create', compact('ujians', 'mapels', 'kelasList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ujian_id' => 'required|exists:ujians,id',
            'mapel_id' => 'required|exists:mapels,id',
            'kelas' => 'required|exists:kelas,nama_kelas',
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

        return redirect()->route('admin.soal.detail', [
            'mapel_id' => $data['mapel_id'],
            'kelas' => $data['kelas']
        ])->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(Soal $soal)
    {
        $ujians = Ujian::all();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('admin.soal.edit', compact('soal', 'ujians', 'mapels', 'kelasList'));
    }

    public function update(Request $request, Soal $soal)
    {
        $data = $request->validate([
            'ujian_id' => 'required|exists:ujians,id',
            'mapel_id' => 'required|exists:mapels,id',
            'kelas' => 'required|exists:kelas,nama_kelas',
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

        return redirect()->route('admin.soal.detail', [
            'mapel_id' => $data['mapel_id'],
            'kelas' => $data['kelas']
        ])->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Soal $soal)
    {
        $mapel_id = $soal->mapel_id;
        $kelas = $soal->kelas;

        $imageFields = ['gambar_soal', 'gambar_a', 'gambar_b', 'gambar_c', 'gambar_d', 'gambar_e'];
        foreach ($imageFields as $field) {
            if ($soal->$field) Storage::disk('public')->delete($soal->$field);
        }

        $soal->delete();

        return redirect()->route('admin.soal.detail', [
            'mapel_id' => $mapel_id,
            'kelas' => $kelas
        ])->with('success', 'Soal berhasil dihapus.');
    }
}

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
use App\Models\PaketSoal;
use App\Imports\SoalImport;
use Maatwebsite\Excel\Facades\Excel;

class AdminSoalController extends Controller
{
    public function index()
    {
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        // Build a list of mapel+kelas combinations that have at least one PaketSoal
        $mapelKelas = PaketSoal::with('mapel')
            ->select('mapel_id', 'kelas')
            ->selectRaw('COUNT(*) as paket_count')
            ->selectRaw('(SELECT COUNT(*) FROM soals WHERE soals.paket_soal_id IN (SELECT id FROM paket_soals AS ps WHERE ps.mapel_id = paket_soals.mapel_id AND ps.kelas = paket_soals.kelas)) as soal_count')
            ->groupBy('mapel_id', 'kelas')
            ->get();

        return view('admin.soal.index', compact('mapels', 'kelasList', 'mapelKelas'));
    }

    public function detail(Request $request)
    {
        $mapel_id = $request->mapel_id;
        $kelas = $request->kelas;

        abort_if(!$mapel_id || !$kelas, 404);

        $mapel = Mapel::findOrFail($mapel_id);
        $pakets = PaketSoal::where('mapel_id', $mapel_id)
            ->where('kelas', $kelas)
            ->withCount('soals')
            ->get();

        return view('admin.soal.detail', compact('mapel', 'kelas', 'pakets'));
    }

    public function storePaket(Request $request)
    {
        $data = $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'kelas' => 'required|string',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);
        PaketSoal::create($data);
        return redirect()->back()->with('success', 'Klasifikasi soal berhasil ditambahkan.');
    }

    public function updatePaket(Request $request, PaketSoal $paket_soal)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);
        $paket_soal->update($data);
        return redirect()->back()->with('success', 'Klasifikasi soal berhasil diperbarui.');
    }

    public function destroyPaket(PaketSoal $paket_soal)
    {
        $soals = $paket_soal->soals;
        $imageFields = ['gambar_soal', 'gambar_a', 'gambar_b', 'gambar_c', 'gambar_d', 'gambar_e'];
        foreach ($soals as $soal) {
            foreach ($imageFields as $field) {
                if ($soal->$field) Storage::disk('public')->delete($soal->$field);
            }
            $soal->delete();
        }
        $paket_soal->delete();
        return redirect()->back()->with('success', 'Klasifikasi soal berhasil dihapus.');
    }

    public function paket(PaketSoal $paket_soal)
    {
        $mapel = $paket_soal->mapel;
        $kelas = $paket_soal->kelas;
        $soals = $paket_soal->soals()->with('ujian')->latest()->get();

        return view('admin.soal.paket', compact('paket_soal', 'mapel', 'kelas', 'soals'));
    }

    public function importForm(PaketSoal $paket_soal)
    {
        return view('admin.soal.import', compact('paket_soal'));
    }

    public function import(Request $request, PaketSoal $paket_soal)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new SoalImport($paket_soal);
            Excel::import($import, $request->file('file_excel'));

            return response()->json([
                'status' => 'success',
                'success_count' => $import->successCount,
                'failed_count' => $import->failedCount,
                'errors' => $import->errors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengimport soal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function template()
    {
        $export = new class implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            public function headings(): array {
                return ['teks_soal', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e', 'jawaban_benar'];
            }
            public function array(): array {
                return [
                    ['Siapakah penemu bola lampu?', 'Thomas Edison', 'Albert Einstein', 'Isaac Newton', 'Nikola Tesla', 'Galileo Galilei', 'A'],
                ];
            }
        };
        return Excel::download($export, 'template_soal.xlsx');
    }

    public function create(PaketSoal $paket_soal)
    {
        $ujians = Ujian::all();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('admin.soal.create', compact('paket_soal', 'ujians', 'mapels', 'kelasList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ujian_id' => 'required|exists:ujians,id',
            'paket_soal_id' => 'required|exists:paket_soals,id',
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

        $paket = PaketSoal::findOrFail($data['paket_soal_id']);
        $data['mapel_id'] = $paket->mapel_id;
        $data['kelas'] = $paket->kelas;

        Soal::create($data);

        return redirect()->route('admin.soal.paket', $paket->id)->with('success', 'Soal berhasil ditambahkan.');
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
            'paket_soal_id' => 'required|exists:paket_soals,id',
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

        $paket = PaketSoal::findOrFail($data['paket_soal_id']);
        $data['mapel_id'] = $paket->mapel_id;
        $data['kelas'] = $paket->kelas;

        $soal->update($data);

        return redirect()->route('admin.soal.paket', $soal->paket_soal_id)->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Soal $soal)
    {
        $paket_soal_id = $soal->paket_soal_id;

        $imageFields = ['gambar_soal', 'gambar_a', 'gambar_b', 'gambar_c', 'gambar_d', 'gambar_e'];
        foreach ($imageFields as $field) {
            if ($soal->$field) Storage::disk('public')->delete($soal->$field);
        }

        $soal->delete();

        return redirect()->route('admin.soal.paket', $paket_soal_id)->with('success', 'Soal berhasil dihapus.');
    }

    public function empty(PaketSoal $paket_soal)
    {
        $soals = $paket_soal->soals;
        $imageFields = ['gambar_soal', 'gambar_a', 'gambar_b', 'gambar_c', 'gambar_d', 'gambar_e'];
        
        foreach ($soals as $soal) {
            foreach ($imageFields as $field) {
                if ($soal->$field) Storage::disk('public')->delete($soal->$field);
            }
            $soal->delete();
        }

        return redirect()->route('admin.soal.paket', $paket_soal->id)->with('success', 'Semua soal berhasil dikosongkan.');
    }
}

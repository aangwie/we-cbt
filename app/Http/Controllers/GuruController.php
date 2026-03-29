<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\HasilUjian;
use App\Models\PaketSoal;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;
use App\Imports\SoalImport;
use Maatwebsite\Excel\Facades\Excel;

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
        $validMapelIds = $guru->mapels()->pluck('mapels.id')->toArray();

        $mapels = $guru->mapels()->orderBy('nama_mapel')->get();
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();

        $mapelKelas = PaketSoal::with('mapel')
            ->whereIn('mapel_id', $validMapelIds)
            ->select('mapel_id', 'kelas')
            ->selectRaw('COUNT(*) as paket_count')
            ->selectRaw('(SELECT COUNT(*) FROM soals WHERE soals.paket_soal_id IN (SELECT id FROM paket_soals AS ps WHERE ps.mapel_id = paket_soals.mapel_id AND ps.kelas = paket_soals.kelas)) as soal_count')
            ->groupBy('mapel_id', 'kelas')
            ->get();

        return view('guru.soal.index', compact('mapels', 'kelasList', 'mapelKelas'));
    }

    public function soalDetail(Request $request)
    {
        $guru = auth()->user();
        $validMapels = $guru->mapels()->pluck('mapels.id')->toArray();

        $mapel_id = $request->mapel_id;
        $kelas = $request->kelas;

        abort_if(!$mapel_id || !$kelas || !in_array($mapel_id, $validMapels), 404);

        $mapel = \App\Models\Mapel::findOrFail($mapel_id);
        $pakets = PaketSoal::where('mapel_id', $mapel_id)
            ->where('kelas', $kelas)
            ->withCount('soals')
            ->get();

        return view('guru.soal.detail', compact('mapel', 'kelas', 'pakets'));
    }

    public function soalStorePaket(Request $request)
    {
        $guru = auth()->user();
        $validMapels = $guru->mapels()->pluck('mapels.id')->toArray();

        $data = $request->validate([
            'mapel_id' => 'required|in:' . implode(',', $validMapels),
            'kelas' => 'required|string',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);
        PaketSoal::create($data);
        return redirect()->back()->with('success', 'Klasifikasi soal berhasil ditambahkan.');
    }

    public function soalUpdatePaket(Request $request, PaketSoal $paket_soal)
    {
        $guru = auth()->user();
        $validMapels = $guru->mapels()->pluck('mapels.id')->toArray();
        abort_if(!in_array($paket_soal->mapel_id, $validMapels), 403);

        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);
        $paket_soal->update($data);
        return redirect()->back()->with('success', 'Klasifikasi soal berhasil diperbarui.');
    }

    public function soalDestroyPaket(PaketSoal $paket_soal)
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

    public function soalPaket(PaketSoal $paket_soal)
    {
        $mapel = $paket_soal->mapel;
        $kelas = $paket_soal->kelas;
        $soals = $paket_soal->soals()->with('ujian')->latest()->get();

        return view('guru.soal.paket', compact('paket_soal', 'mapel', 'kelas', 'soals'));
    }

    public function soalImportForm(PaketSoal $paket_soal)
    {
        return view('guru.soal.import', compact('paket_soal'));
    }

    public function soalImport(Request $request, PaketSoal $paket_soal)
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

    public function soalTemplate()
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

    public function soalCreate(PaketSoal $paket_soal)
    {
        $guru = auth()->user();
        $ujians = Ujian::where('guru_id', $guru->id)->get();
        $mapels = $guru->mapels()->orderBy('nama_mapel')->get();
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();
        return view('guru.soal.create', compact('paket_soal', 'ujians', 'mapels', 'kelasList'));
    }

    public function soalStore(Request $request)
    {
        $guru = auth()->user();

        $validMapels = $guru->mapels()->pluck('mapels.id')->toArray();
        $request->validate([
            'ujian_id' => 'required|exists:ujians,id',
            'paket_soal_id' => 'required|exists:paket_soals,id',
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
            'ujian_id', 'paket_soal_id', 'teks_soal',
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

        $paket = PaketSoal::findOrFail($data['paket_soal_id']);
        $data['mapel_id'] = $paket->mapel_id;
        $data['kelas'] = $paket->kelas;

        Soal::create($data);

        return redirect()->route('guru.soal.paket', $paket->id)->with('success', 'Soal berhasil ditambahkan.');
    }

    public function soalEdit(Soal $soal)
    {
        $guru = auth()->user();
        // Verify ownership
        $ujian = Ujian::where('id', $soal->ujian_id)->where('guru_id', $guru->id)->firstOrFail();
        $ujians = Ujian::where('guru_id', $guru->id)->get();
        $mapels = $guru->mapels()->orderBy('nama_mapel')->get();
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();

        return view('guru.soal.edit', compact('soal', 'ujians', 'mapels', 'kelasList'));
    }

    public function soalUpdate(Request $request, Soal $soal)
    {
        $guru = auth()->user();
        Ujian::where('id', $soal->ujian_id)->where('guru_id', $guru->id)->firstOrFail();

        $validMapels = $guru->mapels()->pluck('mapels.id')->toArray();
        $request->validate([
            'ujian_id' => 'required|exists:ujians,id',
            'paket_soal_id' => 'required|exists:paket_soals,id',
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
            'ujian_id', 'paket_soal_id', 'teks_soal',
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

        $paket = PaketSoal::findOrFail($data['paket_soal_id']);
        $data['mapel_id'] = $paket->mapel_id;
        $data['kelas'] = $paket->kelas;

        $soal->update($data);

        return redirect()->route('guru.soal.paket', $soal->paket_soal_id)->with('success', 'Soal berhasil diperbarui.');
    }

    public function soalDestroy(Soal $soal)
    {
        $guru = auth()->user();
        Ujian::where('id', $soal->ujian_id)->where('guru_id', $guru->id)->firstOrFail();

        $paket_soal_id = $soal->paket_soal_id;

        // Delete associated images
        $imageFields = ['gambar_soal', 'gambar_a', 'gambar_b', 'gambar_c', 'gambar_d', 'gambar_e'];
        foreach ($imageFields as $field) {
            if ($soal->{$field}) {
                Storage::disk('public')->delete($soal->{$field});
            }
        }

        $soal->delete();

        return redirect()->route('guru.soal.paket', $paket_soal_id)->with('success', 'Soal berhasil dihapus.');
    }

    public function soalEmpty(PaketSoal $paket_soal)
    {
        $guru = auth()->user();
        $validMapels = $guru->mapels()->pluck('mapels.id')->toArray();
        abort_if(!in_array($paket_soal->mapel_id, $validMapels), 404);

        $soals = $paket_soal->soals;
        $imageFields = ['gambar_soal', 'gambar_a', 'gambar_b', 'gambar_c', 'gambar_d', 'gambar_e'];
        
        foreach ($soals as $soal) {
            foreach ($imageFields as $field) {
                if ($soal->{$field}) {
                    Storage::disk('public')->delete($soal->{$field});
                }
            }
            $soal->delete();
        }

        return redirect()->route('guru.soal.paket', $paket_soal->id)->with('success', 'Semua soal berhasil dikosongkan.');
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

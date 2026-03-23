<?php

namespace App\Http\Controllers;

use App\Models\HasilUjian;
use Illuminate\Http\Request;

class AdminHasilController extends Controller
{
    public function index()
    {
        $hasils = HasilUjian::with(['siswa', 'ujian'])->latest()->get();
        return view('admin.hasil.index', compact('hasils'));
    }

    public function edit(HasilUjian $hasilUjian)
    {
        $hasilUjian->load(['siswa', 'ujian']);
        return view('admin.hasil.edit', ['hasil' => $hasilUjian]);
    }

    public function update(Request $request, HasilUjian $hasilUjian)
    {
        $request->validate([
            'jumlah_benar' => 'required|integer|min:0',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $hasilUjian->update([
            'jumlah_benar' => $request->jumlah_benar,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('admin.hasil.index')->with('success', 'Hasil ujian berhasil diperbarui.');
    }

    public function destroy(HasilUjian $hasilUjian)
    {
        $hasilUjian->delete();
        return redirect()->route('admin.hasil.index')->with('success', 'Hasil ujian berhasil dihapus.');
    }
}

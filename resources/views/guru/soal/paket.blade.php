@extends('layouts.app')
@section('title', 'Detail Soal - WeTest')
@section('page-title', 'Detail Bank Soal')
@section('sidebar') @include('guru.partials.sidebar') @endsection

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('guru.soal.index') }}" class="p-1.5 bg-white border border-slate-200 rounded-lg text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h2 class="text-xl font-bold text-slate-800">{{ $mapel->nama_mapel }}</h2>
            <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-md border border-blue-200">Kelas {{ $kelas }}</span>
        </div>
        <p class="text-sm text-slate-500 ml-9">Total: <span class="font-semibold text-slate-700">{{ $soals->count() }}</span> Soal Tersedia</p>
    </div>
    
    <div class="flex items-center gap-2">
        @if($soals->count() > 0)
        <form id="empty-form" action="{{ route('guru.soal.empty') }}" method="POST" class="inline">
            @csrf @method('DELETE')
            <input type="hidden" name="mapel_id" value="{{ $mapel->id }}">
            <input type="hidden" name="kelas" value="{{ $kelas }}">
            <button type="button" onclick="confirmAction('empty-form', 'Kosongkan Semua Soal?', 'Peringatan: Menghapus semua soal di halaman ini bersifat permanen.')" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition shadow-lg shadow-red-500/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Kosongkan
            </button>
        </form>
        @endif
        <a href="{{ route('guru.soal.import.form', ['mapel_id' => $mapel->id, 'kelas' => $kelas]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/25">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Import Soal
        </a>
        <a href="{{ route('guru.soal.create', ['mapel_id' => $mapel->id, 'kelas' => $kelas]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Soal Spesifik
        </a>
    </div>
</div>

<div class="bg-white border text-sm border-slate-100 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50/80">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-16">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pertanyaan</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Kunci</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Ujian Referensi</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($soals as $i => $soal)
                <tr class="hover:bg-slate-50/50 group">
                    <td class="px-6 py-4 text-slate-500 text-center">{{ $i + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="text-slate-800 line-clamp-2 leading-relaxed">{!! nl2br(e($soal->teks_soal)) !!}</div>
                        @if($soal->gambar_soal)
                            <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-1 bg-slate-100 text-slate-600 rounded text-[10px] font-medium border border-slate-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Ada Gambar
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded bg-emerald-100 text-emerald-700 font-bold text-xs border border-emerald-200">
                            {{ strtoupper($soal->jawaban_benar) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs text-slate-500">{{ $soal->ujian ? $soal->ujian->judul : 'Bank Soal' }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('guru.soal.edit', $soal) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form id="delete-form-{{ $soal->id }}" action="{{ route('guru.soal.destroy', $soal) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmAction('delete-form-{{ $soal->id }}', 'Hapus Data Soal?', 'Peringatan: Menghapus soal ini bersifat permanen.')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-12 text-center text-slate-400">Silakan tambahkan soal untuk kelas dan mapel ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

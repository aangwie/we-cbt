@extends('layouts.app')
@section('title', 'Kelola Soal - WeTest')
@section('page-title', 'Kelola Soal (Bank Soal Diri)')
@section('sidebar') @include('guru.partials.sidebar') @endsection

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <p class="text-sm text-slate-500">Rekapitulasi pertanyaan khusus pada Mapel yang Anda ampu.</p>
    <a href="{{ route('guru.soal.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Soal Baru
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50/80">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kelas</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah Soal</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($summaries as $i => $row)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 text-slate-500">{{ $i + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800">{{ $row->nama_mapel }}</div>
                        <div class="text-xs text-slate-400 mt-0.5">{{ $row->kode_mapel }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            Kelas {{ $row->kelas }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2.5 rounded-full bg-slate-100 text-slate-700 font-bold text-xs ring-1 ring-slate-200">
                            {{ $row->total_soal }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('guru.soal.detail', ['mapel_id' => $row->mapel_id, 'kelas' => $row->kelas]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-50 hover:text-blue-600 hover:border-blue-200 transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Lihat Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada soal pada mapel yang Anda ampu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

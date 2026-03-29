@extends('layouts.app')
@section('title', 'Kelola Ujian - WeTest')
@section('page-title', 'Kelola Ujian')
@section('sidebar') @include('guru.partials.sidebar') @endsection

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-slate-500">Total: <span class="font-semibold text-slate-700">{{ $ujians->count() }}</span> ujian</p>
        <a href="{{ route('guru.ujian.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Ujian
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Guru</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Token</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Soal</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Durasi</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kelas</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ujians as $i => $ujian)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-3.5 text-slate-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-3.5 font-medium text-slate-800">{{ $ujian->judul }}</td>
                        <td class="px-6 py-3.5">
                            @if($ujian->mapel)
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-md text-xs font-bold">{{ $ujian->mapel->nama_mapel }}</span>
                            @else
                                <span class="text-xs text-slate-400 italic">Belum diset</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $ujian->guru->name }}</td>
                        <td class="px-6 py-3.5"><code class="px-2.5 py-1 bg-slate-100 rounded-lg text-xs font-mono font-bold text-slate-700">{{ $ujian->token }}</code></td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $ujian->soals_count }}</td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $ujian->durasi }} menit</td>
                        <td class="px-6 py-3.5">
                            @if($ujian->kelas)
                                <span class="inline-flex px-2.5 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">{{ $ujian->kelas }}</span>
                            @else
                                <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5">
                            @if($ujian->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Aktif</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-semibold"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('guru.ujian.show', $ujian) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('guru.ujian.edit', $ujian) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                {{-- Regenerate Token --}}
                                <form id="regenToken{{ $ujian->id }}" action="{{ route('guru.ujian.regenerate-token', $ujian) }}" method="POST" class="hidden">@csrf</form>
                                <button type="button" onclick="confirmAction('regenToken{{ $ujian->id }}', 'Regenerate Token?', 'Token ujian lama akan diganti dengan yang baru.', 'question')" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition cursor-pointer" title="Regenerate Token">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                </button>
                                {{-- Delete Ujian --}}
                                <form id="deleteUjian{{ $ujian->id }}" action="{{ route('guru.ujian.destroy', $ujian) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                <button type="button" onclick="confirmAction('deleteUjian{{ $ujian->id }}', 'Hapus Ujian?', 'Ujian beserta semua soal di dalamnya akan dihapus permanen.', 'warning')" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-6 py-8 text-center text-slate-400">Belum ada ujian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

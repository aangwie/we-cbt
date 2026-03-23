@extends('layouts.app')
@section('title', 'Kelola Soal - WeTest')
@section('page-title', 'Kelola Soal')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-slate-500">Soal dari semua ujian di dalam sistem.</p>
        <a href="{{ route('admin.soal.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Soal
        </a>
    </div>

    @forelse($ujians as $ujian)
        @if($ujian->soals->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-800">{{ $ujian->judul }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $ujian->soals->count() }} soal • Guru: {{ $ujian->guru->name }}</p>
                </div>
                @if($ujian->is_active)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Aktif</span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-semibold"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>Nonaktif</span>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Soal</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jawaban</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($ujian->soals as $i => $soal)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-6 py-3.5 text-slate-500">{{ $i + 1 }}</td>
                            <td class="px-6 py-3.5 text-slate-800 max-w-xs truncate">{{ Str::limit($soal->teks_soal, 80) }}</td>
                            <td class="px-6 py-3.5"><span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md text-xs font-bold uppercase">{{ $soal->jawaban_benar }}</span></td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.soal.edit', $soal) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.soal.destroy', $soal) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @empty
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 text-center text-slate-400">
        Belum ada ujian dan soal dalam sistem.
    </div>
    @endforelse
@endsection

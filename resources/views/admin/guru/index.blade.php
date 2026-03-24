@extends('layouts.app')
@section('title', 'Kelola Guru - WeTest')
@section('page-title', 'Kelola Guru')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-slate-500">Total: <span class="font-semibold text-slate-700">{{ $gurus->count() }}</span> guru</p>
        <a href="{{ route('admin.guru.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Guru
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Mapel Diampu</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($gurus as $i => $guru)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-3.5 text-slate-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-3.5 font-medium text-slate-800">{{ $guru->name }}</td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $guru->email }}</td>
                        <td class="px-6 py-3.5">
                            <div class="flex flex-wrap gap-1.5">
                                @forelse($guru->mapels as $mapel)
                                    <span class="inline-block px-2 py-1 bg-indigo-50 text-indigo-700 rounded text-[10px] font-bold tracking-wide uppercase">{{ $mapel->kode_mapel }}</span>
                                @empty
                                    <span class="text-xs text-slate-400 italic">Belum diset</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.guru.edit', $guru) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.guru.destroy', $guru) }}" method="POST" onsubmit="return confirm('Hapus guru ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada guru.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

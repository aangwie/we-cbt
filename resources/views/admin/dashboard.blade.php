@extends('layouts.app')
@section('title', 'Dashboard Admin - WeTest')
@section('page-title', 'Dashboard')

@section('sidebar')
    @include('admin.partials.sidebar')
@endsection

@section('content')
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalGuru }}</p>
            <p class="text-sm text-slate-500 mt-0.5">Total Guru</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalSiswa }}</p>
            <p class="text-sm text-slate-500 mt-0.5">Total Siswa</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-violet-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalUjian }}</p>
            <p class="text-sm text-slate-500 mt-0.5">Total Ujian</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalHasil }}</p>
            <p class="text-sm text-slate-500 mt-0.5">Hasil Ujian</p>
        </div>
    </div>

    {{-- Recent Ujian --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Ujian Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Guru</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Token</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentUjians as $ujian)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-3.5 font-medium text-slate-800">{{ $ujian->judul }}</td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $ujian->guru->name }}</td>
                        <td class="px-6 py-3.5"><code class="px-2.5 py-1 bg-slate-100 rounded-lg text-xs font-mono font-bold text-slate-700">{{ $ujian->token }}</code></td>
                        <td class="px-6 py-3.5">
                            @if($ujian->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Aktif</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-semibold"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada ujian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

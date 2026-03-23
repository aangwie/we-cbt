@extends('layouts.app')
@section('title', 'Dashboard Guru - WeTest')
@section('page-title', 'Dashboard')
@section('sidebar') @include('guru.partials.sidebar') @endsection

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="w-11 h-11 bg-violet-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $ujians->count() }}</p>
            <p class="text-sm text-slate-500 mt-0.5">Ujian Saya</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalSoal }}</p>
            <p class="text-sm text-slate-500 mt-0.5">Total Soal</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="w-11 h-11 bg-emerald-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalHasil }}</p>
            <p class="text-sm text-slate-500 mt-0.5">Hasil Masuk</p>
        </div>
    </div>

    {{-- Ujian List --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Ujian Saya</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah Soal</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ujians as $ujian)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-3.5 font-medium text-slate-800">{{ $ujian->judul }}</td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $ujian->soals_count }}</td>
                        <td class="px-6 py-3.5">
                            @if($ujian->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Aktif</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-semibold"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-8 text-center text-slate-400">Belum ada ujian yang ditugaskan kepada Anda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

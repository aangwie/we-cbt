@extends('layouts.app')
@section('title', 'Konfirmasi Ujian - WeTest')
@section('page-title', 'Mulai Ujian')
@section('sidebar') @include('siswa.partials.sidebar') @endsection

@section('content')
    <div class="max-w-4xl mx-auto">
         
        {{-- Data Siswa --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Biodata Peserta
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500">Nama Lengkap</p>
                    <p class="text-sm font-semibold text-slate-800 mt-0.5 truncate">{{ $siswa->name }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500">NISN</p>
                    <p class="text-sm font-semibold text-slate-800 mt-0.5 font-mono">{{ $siswa->nisn }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500">Kelas</p>
                    <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $siswa->kelas }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500">Jenis Kelamin</p>
                    <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
            </div>
        </div>

        {{-- Daftar Ujian Tersedia --}}
        <div>
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Mata Pelajaran Ujian (Aktif)
            </h3>
            
            @if($activeUjians->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <p class="text-slate-500">Saat ini tidak ada ujian yang dijadwalkan untuk kelas Anda.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($activeUjians as $ujian)
                    <a href="{{ route('siswa.ujian.konfirmasi', $ujian->id) }}" class="bg-gradient-to-br from-purple-50 to-white border border-purple-100 rounded-2xl shadow-sm hover:shadow-lg hover:border-purple-200 hover:-translate-y-1 transition-all duration-300 overflow-hidden group flex flex-col h-full cursor-pointer">
                        
                        <div class="p-6 flex-1 flex flex-col relative z-10">
                            {{-- Decorative SVG Triangle Removed --}}
                            
                            <div class="flex items-start justify-between mb-4 gap-2 relative z-10">
                                <h4 class="font-bold text-purple-900 text-xl leading-tight">
                                    {{ $ujian->mapel ? $ujian->mapel->nama_mapel : $ujian->judul }}
                                </h4>
                                <span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2 py-1 rounded whitespace-nowrap shadow-sm border border-purple-200/50">
                                    {{ $ujian->durasi }} Menit
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mb-1 flex items-center gap-1.5 line-clamp-1 relative z-10 font-medium">
                                <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                {{ $ujian->judul }}
                            </p>
                            <p class="text-xs text-slate-500 flex items-center gap-1.5 truncate relative z-10">
                                <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $ujian->guru->name }}
                            </p>
                            
                            <div class="mt-6 pt-4 border-t border-purple-100/50 w-full mt-auto text-center relative z-10">
                                <span class="text-sm font-semibold text-purple-600 flex items-center justify-center gap-1.5 py-1 group-hover:text-purple-700 transition-colors">
                                    Mulai Ujian Ini
                                    <svg class="w-4 h-4 group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

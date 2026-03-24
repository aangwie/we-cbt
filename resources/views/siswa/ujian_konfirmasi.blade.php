@extends('layouts.app')
@section('title', 'Masukkan Token Ujian - WeTest')
@section('page-title', 'Token Ujian')
@section('sidebar') @include('siswa.partials.sidebar') @endsection

@section('content')
    <div class="max-w-xl mx-auto mt-8">
        {{-- Back Button --}}
        <a href="{{ route('siswa.konfirmasi') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Ujian
        </a>

        {{-- Token Input Box --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xl shadow-purple-900/5 overflow-hidden">
            <div class="bg-gradient-to-br from-purple-50 to-white px-8 py-8 border-b border-purple-100/50 flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-5 rotate-3 shadow-sm border border-purple-200/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight">Masukkan Token</h3>
                <p class="text-slate-500 mt-2">Anda akan memulai ujian <strong class="text-purple-700 font-bold drop-shadow-sm">{{ $ujian->mapel ? $ujian->mapel->nama_mapel : $ujian->judul }}</strong></p>
                
                <div class="bg-amber-50 border border-amber-200/60 rounded-xl px-4 py-2 text-sm text-amber-700 font-medium mt-5 flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Waktu {{ $ujian->durasi }} menit berjalan otomatis
                </div>
            </div>

            <div class="p-8">
                @if($errors->has('token'))
                    <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $errors->first('token') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('siswa.validate-token') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="ujian_id" value="{{ $ujian->id }}">
                    
                    <div>
                        <input type="text" name="token" value="{{ old('token') }}" required autofocus placeholder="Ketik token di sini" class="w-full px-6 py-5 bg-slate-50 border-2 border-slate-200 rounded-2xl text-center text-3xl font-mono font-bold tracking-[0.2em] text-slate-800 placeholder:text-slate-300 placeholder:font-sans placeholder:font-medium placeholder:tracking-normal placeholder:text-lg focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 focus:bg-white transition-all outline-none uppercase" maxlength="10" autocomplete="off">
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-lg rounded-2xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-xl shadow-purple-500/25 flex items-center justify-center gap-2 group">
                        Konfirmasi Token
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>
        </div>
        
    </div>
@endsection

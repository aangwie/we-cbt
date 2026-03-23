@extends('layouts.app')
@section('title', 'Konfirmasi Ujian - WeTest')
@section('page-title', 'Mulai Ujian')
@section('sidebar') @include('siswa.partials.sidebar') @endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        {{-- Data Siswa --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Data Siswa
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500">Nama</p>
                    <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $siswa->name }}</p>
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

        {{-- Token Input --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                Masukkan Token Ujian
            </h3>
            <p class="text-sm text-slate-500 mb-4">Masukkan token ujian yang diberikan oleh guru atau admin untuk memulai ujian.</p>

            @if($errors->has('token'))
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm">{{ $errors->first('token') }}</div>
            @endif

            <form method="POST" action="{{ route('siswa.validate-token') }}" class="space-y-4">
                @csrf
                <div>
                    <input type="text" name="token" value="{{ old('token') }}" required autofocus placeholder="Masukkan token ujian (contoh: ABC123)" class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-xl text-center text-lg font-mono font-bold tracking-widest focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none uppercase" maxlength="10">
                </div>
                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-lg shadow-emerald-500/25 text-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    Mulai Ujian
                </button>
            </form>
        </div>
    </div>
@endsection

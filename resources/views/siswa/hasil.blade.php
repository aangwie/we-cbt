@extends('layouts.app')
@section('title', 'Hasil Ujian - WeTest')
@section('page-title', 'Hasil Ujian')
@section('sidebar') @include('siswa.partials.sidebar') @endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        {{-- Score Card --}}
        <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-2xl p-8 text-white text-center mb-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Ccircle%20cx%3D%2230%22%20cy%3D%2230%22%20r%3D%221.5%22%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.07%22%2F%3E%3C%2Fsvg%3E')]"></div>
            <div class="relative">
                <p class="text-slate-400 text-sm mb-2">{{ $hasil->ujian->judul }}</p>
                <div class="w-28 h-28 mx-auto rounded-full flex items-center justify-center mb-4 {{ $hasil->nilai >= 70 ? 'bg-gradient-to-br from-emerald-400 to-teal-500' : 'bg-gradient-to-br from-red-400 to-rose-500' }} shadow-2xl">
                    <span class="text-3xl font-black">{{ number_format($hasil->nilai, 0) }}</span>
                </div>
                <p class="text-lg font-bold mb-1">
                    @if($hasil->nilai >= 70)
                        🎉 Selamat, Lulus!
                    @else
                        😅 Belum Lulus
                    @endif
                </p>
                <p class="text-slate-400 text-sm">{{ $hasil->jumlah_benar }} dari {{ $totalSoal }} soal benar</p>
            </div>
        </div>

        {{-- Detail --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <h3 class="text-base font-bold text-slate-800 mb-4">Detail Hasil</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500">Mata Pelajaran</p>
                    <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $hasil->ujian->judul }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500">Total Soal</p>
                    <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $totalSoal }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500">Jawaban Benar</p>
                    <p class="text-sm font-semibold text-emerald-600 mt-0.5">{{ $hasil->jumlah_benar }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3">
                    <p class="text-xs text-slate-500">Nilai</p>
                    <p class="text-sm font-semibold {{ $hasil->nilai >= 70 ? 'text-emerald-600' : 'text-red-600' }} mt-0.5">{{ $hasil->nilai }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl px-4 py-3 col-span-2">
                    <p class="text-xs text-slate-500">Waktu Selesai</p>
                    <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $hasil->tgl_selesai->format('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('siswa.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
@endsection

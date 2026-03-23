@extends('layouts.app')
@section('title', 'Edit Hasil Ujian - WeTest')
@section('page-title', 'Edit Hasil Ujian')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
    <div class="max-w-xl">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Edit Nilai: {{ $hasil->siswa->name }}</h3>
            <p class="text-sm text-slate-500 mb-6">Ujian: <span class="font-semibold text-slate-700">{{ $hasil->ujian->judul }}</span></p>

            <form action="{{ route('admin.hasil.update', $hasil) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah Benar</label>
                    <input type="number" name="jumlah_benar" value="{{ old('jumlah_benar', $hasil->jumlah_benar) }}" min="0" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nilai Akhir</label>
                    <input type="number" step="0.01" name="nilai" value="{{ old('nilai', $hasil->nilai) }}" min="0" max="100" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                    <p class="text-xs text-slate-400 mt-1">Skala 0 - 100</p>
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">Simpan Perubahan</button>
                    <a href="{{ route('admin.hasil.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

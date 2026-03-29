@extends('layouts.app')
@section('title', 'Tambah Soal - WeTest')
@section('page-title', 'Tambah Soal')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
    <div class="max-w-3xl">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('admin.soal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ujian</label>
                    <select name="ujian_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                        <option value="">Pilih Ujian...</option>
                        @foreach($ujians as $ujian)
                            <option value="{{ $ujian->id }}" {{ old('ujian_id') == $ujian->id ? 'selected' : '' }}>{{ $ujian->judul }} ({{ $ujian->guru->name }})</option>
                        @endforeach
                    </select>
                <input type="hidden" name="paket_soal_id" value="{{ $paket_soal->id }}">
                <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-xl mb-6">
                    <p class="text-sm text-blue-800 font-medium">Menambahkan soal ke klasifikasi: <span class="font-bold">{{ $paket_soal->judul }}</span></p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Teks Soal</label>
                    <textarea name="teks_soal" rows="3" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none resize-none">{{ old('teks_soal') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Gambar Soal <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <input type="file" name="gambar_soal" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                </div>

                <div class="border-t border-slate-100 pt-6 space-y-4">
                    <h4 class="text-sm font-bold text-slate-700">Pilihan Jawaban</h4>
                    @foreach(['a', 'b', 'c', 'd', 'e'] as $opt)
                    <div class="bg-slate-50 rounded-xl p-4 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center text-xs font-bold uppercase">{{ $opt }}</span>
                            <input type="text" name="pilihan_{{ $opt }}" value="{{ old('pilihan_' . $opt) }}" required placeholder="Teks pilihan {{ strtoupper($opt) }}" class="flex-1 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                        </div>
                        <input type="file" name="gambar_{{ $opt }}" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-600 hover:file:bg-slate-300 transition">
                    </div>
                    @endforeach
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jawaban Benar</label>
                    <select name="jawaban_benar" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                        <option value="">Pilih...</option>
                        @foreach(['a', 'b', 'c', 'd', 'e'] as $opt)
                            <option value="{{ $opt }}" {{ old('jawaban_benar') === $opt ? 'selected' : '' }}>{{ strtoupper($opt) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">Simpan Soal</button>
                    <a href="{{ route('admin.soal.paket', $paket_soal->id) }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

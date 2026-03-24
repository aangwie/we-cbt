@extends('layouts.app')
@section('title', 'Tambah Mata Pelajaran - WeTest')
@section('page-title', 'Tambah Mata Pelajaran')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('admin.mapel.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kode Mapel</label>
                    <input type="text" name="kode_mapel" value="{{ old('kode_mapel') }}" required placeholder="Contoh: MAT, IPA, BIND" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none uppercase">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Mata Pelajaran</label>
                    <input type="text" name="nama_mapel" value="{{ old('nama_mapel') }}" required placeholder="Contoh: Matematika" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">Simpan Data</button>
                    <a href="{{ route('admin.mapel.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.app')
@section('title', 'Edit Kelas - WeTest')
@section('page-title', 'Edit Kelas')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <form action="{{ route('admin.kelas.update', $kelas) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kelas</label>
                <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required placeholder="Contoh: XII-RPL" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
            </div>
            
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">Simpan Perubahan</button>
                <a href="{{ route('admin.kelas.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

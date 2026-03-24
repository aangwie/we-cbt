@extends('layouts.app')
@section('title', 'Edit Guru - WeTest')
@section('page-title', 'Edit Guru')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('admin.guru.update', $guru) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $guru->name) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $guru->email) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mata Pelajaran Yang Diampu <span class="text-xs font-normal text-slate-400">(Bisa pilih lebih dari satu)</span></label>
                    <select name="mapels[]" multiple class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none min-h-[100px]">
                        @php $selectedMapels = collect(old('mapels', $guru->mapels->pluck('id')->toArray())); @endphp
                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}" {{ $selectedMapels->contains($mapel->id) ? 'selected' : '' }}>{{ $mapel->nama_mapel }} ({{ $mapel->kode_mapel }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-500 mt-1.5">Tahan tombol <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded text-slate-600 font-mono">Ctrl</kbd> atau <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-200 rounded text-slate-600 font-mono">Cmd</kbd> untuk memilih beberapa mapel.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password <span class="text-slate-400 font-normal">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">Update</button>
                    <a href="{{ route('admin.guru.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

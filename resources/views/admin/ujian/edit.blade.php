@extends('layouts.app')
@section('title', 'Edit Ujian - WeTest')
@section('page-title', 'Edit Ujian')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('admin.ujian.update', $ujian) }}" method="POST" class="space-y-5" x-data="{ kelas: '{{ old('kelas', $ujian->kelas ?? '') }}' }">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Ujian</label>
                    <input type="text" name="judul" value="{{ old('judul', $ujian->judul) }}" required placeholder="Contoh: UTS Semester 1" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mata Pelajaran</label>
                    <select name="mapel_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                        <option value="">Pilih Mata Pelajaran...</option>
                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}" {{ old('mapel_id', $ujian->mapel_id) == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Guru Pengampu</label>
                    <select name="guru_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ old('guru_id', $ujian->guru_id) == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Durasi (menit)</label>
                    <input type="number" name="durasi" value="{{ old('durasi', $ujian->durasi) }}" required min="1" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                </div>

                {{-- Kelas Selection --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Untuk Kelas</label>
                    <select name="kelas" x-model="kelas" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                        <option value="">Pilih Kelas...</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1.5">Pilih kelas terlebih dahulu untuk mengaktifkan ujian</p>
                </div>

                {{-- Toggle Aktifkan (disabled until kelas selected) --}}
                <div class="flex items-center gap-3" :class="!kelas ? 'opacity-50' : ''">
                    <label class="relative inline-flex items-center" :class="kelas ? 'cursor-pointer' : 'cursor-not-allowed'">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $ujian->is_active) ? 'checked' : '' }} class="sr-only peer" :disabled="!kelas">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                    <span class="text-sm font-medium text-slate-700">Aktifkan ujian</span>
                    <template x-if="!kelas">
                        <span class="text-xs text-amber-600 font-medium">(Pilih kelas dulu)</span>
                    </template>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Token Saat Ini</p>
                        <code class="text-lg font-mono font-bold text-slate-800">{{ $ujian->token }}</code>
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">Update</button>
                    <a href="{{ route('admin.ujian.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

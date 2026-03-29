@extends('layouts.app')
@section('title', 'Buat Ujian - WeTest')
@section('page-title', 'Buat Ujian Baru')
@section('sidebar') @include('guru.partials.sidebar') @endsection

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('guru.ujian.store') }}" method="POST" class="space-y-5" x-data="{
                kelas: '{{ old('kelas', '') }}',
                selectedMapel: '{{ old('mapel_id', '') }}',
                paketSoals: @js($paketSoals->map(fn($p) => ['id' => $p->id, 'judul' => $p->judul, 'kelas' => $p->kelas, 'mapel_id' => $p->mapel_id, 'mapel_nama' => $p->mapel->nama_mapel ?? '-', 'soals_count' => $p->soals_count])),
                get filteredPakets() {
                    if (!this.selectedMapel) return [];
                    return this.paketSoals.filter(p => p.mapel_id == this.selectedMapel);
                }
            }">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Ujian</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Contoh: UTS Semester 1" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mata Pelajaran</label>
                    <select name="mapel_id" x-model="selectedMapel" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                        <option value="">Pilih Mata Pelajaran...</option>
                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Paket Soal yang Tersedia --}}
                <div x-show="selectedMapel" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200/60 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span class="text-sm font-semibold text-emerald-800">Pilih Paket Soal</span>
                        </div>

                        <template x-if="filteredPakets.length === 0">
                            <p class="text-xs text-emerald-600/70 italic">Belum ada paket soal untuk mata pelajaran ini.</p>
                        </template>

                        <div class="space-y-2" x-show="filteredPakets.length > 0">
                            <template x-for="paket in filteredPakets" :key="paket.id">
                                <label class="flex items-center justify-between bg-white/70 backdrop-blur-sm rounded-lg px-3.5 py-2.5 border cursor-pointer transition hover:border-emerald-400"
                                       :class="selectedPaket === paket.id ? 'border-emerald-500 ring-1 ring-emerald-500 bg-emerald-50/50' : 'border-emerald-100'">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="paket_soal_id" :value="paket.id" x-model="selectedPaket" class="text-emerald-600 focus:ring-emerald-500 w-4 h-4" required>
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-700" x-text="paket.judul"></p>
                                            <p class="text-xs text-slate-400" x-text="'Kelas ' + paket.kelas"></p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        <span x-text="paket.soals_count"></span> soal
                                    </span>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Durasi (menit)</label>
                    <input type="number" name="durasi" value="{{ old('durasi', 60) }}" required min="1" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
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
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="sr-only peer" :disabled="!kelas">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                    <span class="text-sm font-medium text-slate-700">Aktifkan ujian</span>
                    <template x-if="!kelas">
                        <span class="text-xs text-amber-600 font-medium">(Pilih kelas dulu)</span>
                    </template>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-blue-700">
                    <strong>Info:</strong> Token ujian akan di-generate otomatis setelah ujian dibuat.
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">Buat Ujian</button>
                    <a href="{{ route('guru.ujian.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

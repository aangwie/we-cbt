@extends('layouts.app')
@section('title', 'Pengaturan Website - WeTest')
@section('page-title', 'Pengaturan Aplikasi')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Form Pengaturan -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 rounded-bl-full -z-10"></div>
                
                <h3 class="text-base font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Informasi Dasar
                </h3>

                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Website</label>
                        <input type="text" name="app_name" value="{{ old('app_name', $setting->app_name) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Logo Website <span class="text-xs font-normal text-slate-400">(Biarkan kosong jika tidak ingin mengubah)</span></label>
                        @if($setting->app_logo)
                            <div class="mb-3 p-3 bg-slate-50 rounded-xl border border-slate-200 inline-block">
                                <img src="{{ asset('storage/' . $setting->app_logo) }}" alt="Logo Saat Ini" class="h-10 object-contain">
                            </div>
                        @endif
                        <input type="file" name="app_logo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition outline-none border border-slate-200 bg-slate-50 rounded-xl p-1">
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Format Tanggal & Waktu</label>
                        <select name="date_format" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition outline-none">
                            <option value="l, d F Y H:i" {{ old('date_format', $setting->date_format ?? 'l, d F Y H:i') == 'l, d F Y H:i' ? 'selected' : '' }}>Senin, 17 Agustus 2024 14:30 (Lengkap)</option>
                            <option value="d F Y H:i" {{ old('date_format', $setting->date_format) == 'd F Y H:i' ? 'selected' : '' }}>17 Agustus 2024 14:30 (Standar)</option>
                            <option value="d/m/Y H:i" {{ old('date_format', $setting->date_format) == 'd/m/Y H:i' ? 'selected' : '' }}>17/08/2024 14:30 (Ringkas)</option>
                            <option value="l, d M Y" {{ old('date_format', $setting->date_format) == 'l, d M Y' ? 'selected' : '' }}>Senin, 17 Agu 2024 (Tanpa Waktu)</option>
                            <option value="d M Y" {{ old('date_format', $setting->date_format) == 'd M Y' ? 'selected' : '' }}>17 Agu 2024 (Singkat)</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">GitHub Token</label>
                        <p class="text-xs text-slate-500 mb-2">Gunakan format <code class="bg-slate-100 px-1 py-0.5 rounded text-indigo-600">ghp_xxxxxx</code></p>
                        <input type="text" name="github_token" value="{{ old('github_token', $setting->github_token) }}" placeholder="ghp_...................................." class="w-full px-4 py-3 bg-slate-50 border {{ $errors->has('github_token') ? 'border-red-300' : 'border-slate-200' }} rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition outline-none font-mono">
                        @if($errors->has('github_token'))
                            <p class="text-red-500 text-xs mt-1">{{ $errors->first('github_token') }}</p>
                        @endif
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-indigo-500/25">Simpan Pengaturan</button>
                    </div>
                </form>
            </div>

            <!-- Terminal Log Viewer -->
            <div class="bg-slate-900 rounded-2xl border border-slate-800 shadow-xl overflow-hidden">
                <div class="px-4 py-3 bg-slate-800/80 border-b border-slate-700/50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-red-500/80"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-500/80"></span>
                            <span class="w-3 h-3 rounded-full bg-green-500/80"></span>
                        </div>
                        <span class="text-xs font-mono text-slate-400 ml-2">storage/logs/system_updates.log</span>
                    </div>
                    <span class="text-xs text-slate-500">Last 100 lines</span>
                </div>
                <div class="p-4 bg-slate-900/95 overflow-x-auto h-80 font-mono text-xs text-slate-300 leading-relaxed custom-scrollbar">
                    <pre class="whitespace-pre-wrap">{{ $terminalLog }}</pre>
                </div>
            </div>
        </div>

        <!-- Quick Actions Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Aksi Sistem
                </h3>
                <p class="text-sm text-slate-500 mb-6">Bersihkan cache atau konfigurasi jika sistem mengalami lag atau pembaruan belum diterapkan.</p>

                <div class="space-y-3">
                    <form id="updateSystemForm" action="{{ route('admin.settings.update-system') }}" method="POST">
                        @csrf
                    </form>
                    <button type="button" onclick="confirmAction('updateSystemForm', 'Update CBT Lokal?', 'Sistem akan memanggil Github Pull dan menjalankan Migrasi Database terbaru (Jika Ada). Pastikan koneksi aman.', 'info')" class="w-full flex items-center justify-between px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold text-sm rounded-xl border border-blue-200 transition cursor-pointer">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Update CBT (GitHub)
                        </span>
                    </button>

                    <div class="border-t border-slate-100 my-4"></div>

                    <form id="clearCacheForm" action="{{ route('admin.settings.clear-cache') }}" method="POST">
                        @csrf
                    </form>
                    <button type="button" onclick="confirmAction('clearCacheForm', 'Clear Cache?', 'Bersihkan semua cache view dan route?', 'question')" class="w-full flex items-center justify-between px-4 py-3 bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold text-sm rounded-xl border border-amber-200 transition cursor-pointer">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Clear Cache
                        </span>
                    </button>

                    <form id="clearConfigForm" action="{{ route('admin.settings.clear-config') }}" method="POST">
                        @csrf
                    </form>
                    <button type="button" onclick="confirmAction('clearConfigForm', 'Clear Config?', 'Bersihkan konfigurasi cache?', 'question')" class="w-full flex items-center justify-between px-4 py-3 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-sm rounded-xl border border-rose-200 transition cursor-pointer">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Clear Config
                        </span>
                    </button>

                    <div class="border-t border-slate-100 my-4"></div>

                    <form id="linkStorageForm" action="{{ route('admin.settings.link-storage') }}" method="POST">
                        @csrf
                    </form>
                    <button type="button" onclick="confirmAction('linkStorageForm', 'Link Storage?', 'Buat symlink folder public/storage agar gambar (logo, berita, soal) tampil di Shared Hosting?', 'question')" class="w-full flex items-center justify-between px-4 py-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold text-sm rounded-xl border border-emerald-200 transition cursor-pointer">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            Link Storage (Fix Gambar)
                        </span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(255, 255, 255, 0.1); border-radius: 10px; }
    </style>
@endsection

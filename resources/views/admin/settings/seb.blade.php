@extends('layouts.app')
@section('title', 'Pengaturan SEB - WeTest')
@section('page-title', 'Pengaturan Safe Exam Browser')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Header Info --}}
        <div
            class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-6 text-white relative overflow-hidden shadow-xl shadow-emerald-500/20">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
            <div class="relative flex items-start gap-4">
                <div class="w-14 h-14 bg-white/15 backdrop-blur rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold mb-1">Safe Exam Browser (SEB)</h2>
                    <p class="text-sm text-white/80 leading-relaxed">SEB adalah aplikasi browser khusus yang mengunci
                        perangkat siswa saat mengerjakan ujian. Siswa tidak dapat membuka tab lain, screenshot, copy-paste,
                        atau mengakses aplikasi lain selama ujian berlangsung.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Form SEB Settings --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 rounded-bl-full -z-10">
                    </div>

                    <h3 class="text-base font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Konfigurasi SEB
                    </h3>

                    <form action="{{ route('admin.settings.seb.update') }}" method="POST" class="space-y-6">
                        @csrf @method('PUT')

                        {{-- Toggle SEB --}}
                        <div class="flex items-center justify-between p-4 rounded-xl border transition-all duration-300"
                            x-data="{ enabled: {{ $setting->seb_enabled ? 'true' : 'false' }} }"
                            :class="enabled ? 'border-emerald-300 bg-emerald-50/60 shadow-sm shadow-emerald-100' : 'border-red-200 bg-red-50/40'">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300"
                                    :class="enabled ? 'bg-emerald-100' : 'bg-red-100'">
                                    <svg x-show="enabled" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <svg x-show="!enabled" class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold transition-colors duration-300"
                                        :class="enabled ? 'text-emerald-800' : 'text-red-700'"
                                        x-text="enabled ? 'Mode SEB Aktif' : 'Mode SEB Nonaktif'"></p>
                                    <p class="text-xs transition-colors duration-300"
                                        :class="enabled ? 'text-emerald-600' : 'text-red-500'"
                                        x-text="enabled ? 'Siswa hanya bisa mengakses ujian melalui Safe Exam Browser' : 'Siswa dapat mengakses ujian dari browser apa pun'">
                                    </p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer" @click="enabled = !enabled">
                                <input type="hidden" name="seb_enabled" :value="enabled ? '1' : '0'">
                                <div class="w-14 h-7 rounded-full transition-all duration-300 relative"
                                    :class="enabled ? 'bg-emerald-500 shadow-lg shadow-emerald-500/30' : 'bg-red-400 shadow-lg shadow-red-400/30'">
                                    <div class="absolute top-0.5 w-6 h-6 bg-white rounded-full shadow-md transition-all duration-300 flex items-center justify-center"
                                        :class="enabled ? 'left-[30px]' : 'left-[2px]'">
                                        <svg x-show="enabled" class="w-3.5 h-3.5 text-emerald-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        <svg x-show="!enabled" class="w-3.5 h-3.5 text-red-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                </div>
                            </label>
                        </div>

                        {{-- Browser Exam Key --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Browser Exam Key (BEK)</label>
                            <p class="text-xs text-slate-500 mb-3">Hash SHA-256 yang dihasilkan oleh konfigurasi SEB Anda.
                                Dapatkan dari menu <code
                                    class="bg-slate-100 px-1.5 py-0.5 rounded text-emerald-600 font-mono text-[11px]">SEB → Exam → Browser Exam Key</code>
                            </p>
                            <div class="relative">
                                <input type="text" name="seb_key" value="{{ old('seb_key', $setting->seb_key) }}"
                                    placeholder="e.g. a1b2c3d4e5f6..." maxlength="64"
                                    class="w-full px-4 py-3 bg-slate-50 border {{ $errors->has('seb_key') ? 'border-red-300' : 'border-slate-200' }} rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none font-mono tracking-wider pr-20">

                            </div>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-mono"
                                id="sebKeyCount">0/64</span>
                            @if($errors->has('seb_key'))
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $errors->first('seb_key') }}
                                </p>
                            @endif
                        </div>

                        {{-- Status Indicator --}}
                        <div
                            class="p-4 rounded-xl border transition-all duration-300 {{ $setting->seb_enabled && $setting->seb_key ? 'border-emerald-200 bg-emerald-50' : ($setting->seb_enabled ? 'border-amber-200 bg-amber-50' : 'border-slate-200 bg-slate-50') }}">
                            <div class="flex items-center gap-3">
                                @if($setting->seb_enabled && $setting->seb_key)
                                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-emerald-800">SEB Aktif & Terkonfigurasi</p>
                                        <p class="text-xs text-emerald-600">Ujian hanya bisa diakses melalui Safe Exam Browser.
                                        </p>
                                    </div>
                                @elseif($setting->seb_enabled)
                                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-amber-800">SEB Aktif, Key Belum Diisi</p>
                                        <p class="text-xs text-amber-600">Masukkan Browser Exam Key agar validasi SEB bekerja.
                                        </p>
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-slate-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-600">SEB Tidak Aktif</p>
                                        <p class="text-xs text-slate-500">Siswa dapat mengakses ujian dari browser apa pun.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition shadow-lg shadow-emerald-500/25 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Pengaturan SEB
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Panduan Sidebar --}}
            <div class="space-y-6">
                {{-- Cara Mendapatkan BEK --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Cara Mendapatkan BEK
                    </h3>
                    <ol class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-2.5">
                            <span
                                class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">1</span>
                            <span>Download & install <a href="https://safeexambrowser.org/download_en.html" target="_blank"
                                    class="text-blue-600 hover:underline font-semibold">Safe Exam Browser</a></span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span
                                class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">2</span>
                            <span>Buka SEB Configuration Tool</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span
                                class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">3</span>
                            <span>Atur <strong>Start URL</strong> ke alamat website CBT Anda</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span
                                class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">4</span>
                            <span>Navigasi ke tab <strong>Exam → Browser Exam Key</strong></span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span
                                class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">5</span>
                            <span>Copy nilai <strong>Browser Exam Key</strong> (hash SHA-256) dan paste di field konfigurasi
                                di samping</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span
                                class="w-6 h-6 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">6</span>
                            <span>Simpan konfigurasi SEB sebagai file <code
                                    class="bg-slate-100 px-1 py-0.5 rounded text-[11px] font-mono">.seb</code> dan
                                distribusikan ke komputer siswa</span>
                        </li>
                    </ol>
                </div>

                {{-- Info SEB --}}
                <div class="bg-gradient-to-br from-slate-50 to-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Fitur Keamanan SEB
                    </h3>
                    <ul class="space-y-2.5 text-sm text-slate-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Blokir akses ke aplikasi lain
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Cegah copy-paste & screenshot
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Nonaktifkan navigasi browser (tab, URL bar)
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Verifikasi identitas browser via BEK
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Fullscreen terkunci saat ujian
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // BEK character counter
            const sebKeyInput = document.querySelector('input[name="seb_key"]');
            const sebKeyCount = document.getElementById('sebKeyCount');
            if (sebKeyInput && sebKeyCount) {
                const updateCount = () => {
                    const len = sebKeyInput.value.length;
                    sebKeyCount.textContent = len + '/64';
                    sebKeyCount.className = 'absolute right-3 top-1/2 -translate-y-1/2 text-xs font-mono ' +
                        (len === 64 ? 'text-emerald-500' : (len > 0 ? 'text-amber-500' : 'text-slate-400'));
                };
                sebKeyInput.addEventListener('input', updateCount);
                updateCount();
            }
        </script>
    @endpush
@endsection
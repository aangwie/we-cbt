@extends('layouts.guest')
@section('title', 'Login - WeTest CBT')

@section('content')
<div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 px-6 py-8 text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2230%22%20height%3D%2230%22%20viewBox%3D%220%200%2030%2030%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M0%2010h10v10H0z%22%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.03%22%2F%3E%3C%2Fsvg%3E')]"></div>
        <div class="relative">
            @if(isset($appSetting) && $appSetting->app_logo)
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg overflow-hidden border border-white/20 p-1">
                    <img src="{{ asset('storage/' . $appSetting->app_logo) }}" alt="Logo" class="w-full h-full object-contain">
                </div>
            @else
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            @endif
            <h1 class="text-2xl font-bold text-white">{{ isset($appSetting) ? $appSetting->app_name : 'WeTest' }}</h1>
            <p class="text-blue-100 text-sm mt-1">Computer Based Test System</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: 'user' }" class="px-6 py-6">
        <div class="flex bg-slate-100 rounded-xl p-1 mb-6">
            <button @click="tab = 'user'" :class="tab === 'user' ? 'bg-white shadow-sm text-blue-600 font-semibold' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-2.5 text-sm rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Admin / Guru
            </button>
            <button @click="tab = 'siswa'" :class="tab === 'siswa' ? 'bg-white shadow-sm text-blue-600 font-semibold' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-2.5 text-sm rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Siswa
            </button>
        </div>

        {{-- Admin/Guru Login --}}
        <div x-show="tab === 'user'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            @if($errors->has('email'))
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm">{{ $errors->first('email') }}</div>
            @endif
            <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="login_type" value="user">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none" placeholder="admin@wetest.com">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                    <input id="password" type="password" name="password" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none" placeholder="••••••••">
                </div>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 text-sm">
                    Masuk
                </button>
            </form>
        </div>

        {{-- Siswa Login --}}
        <div x-show="tab === 'siswa'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            @if($errors->has('nisn'))
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm">{{ $errors->first('nisn') }}</div>
            @endif
            <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="login_type" value="siswa">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">NISN</label>
                    <input id="nisn" type="text" name="nisn" value="{{ old('nisn') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none" placeholder="0012345678">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password <span class="font-normal text-slate-500 text-xs">(Berdasarkan tanggal lahir: DDMMYYYY)</span></label>
                    <input id="password" type="password" name="password" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none" placeholder="Contoh: 18052008">
                </div>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 text-sm">
                    Masuk sebagai Siswa
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Alpine.js --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection

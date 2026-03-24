<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="WeTest - Sistem Computer Based Test">
    <title>@yield('title', 'WeTest CBT')</title>
    @php $setting = \App\Models\Setting::first(); @endphp
    <link rel="icon" type="image/png" href="{{ $setting && $setting->app_logo ? asset('storage/' . $setting->app_logo) : asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media (min-width: 1024px) {
            .sidebar-minimized-width { width: 5rem !important; }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false, sidebarMinimized: JSON.parse(localStorage.getItem('sidebarMinimized')) || false }" x-init="$watch('sidebarMinimized', val => localStorage.setItem('sidebarMinimized', val))">
        {{-- Sidebar Overlay (mobile) --}}
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 lg:hidden" style="display:none;"></div>

        {{-- Sidebar --}}
        <aside :class="{
            'translate-x-0': sidebarOpen,
            '-translate-x-full': !sidebarOpen,
            'sidebar-minimized-width': sidebarMinimized
        }" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 text-white transform transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-2xl">
            {{-- Logo --}}
            <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
                @if(isset($appSetting) && $appSetting->app_logo)
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg p-0.5 overflow-hidden">
                        <img src="{{ asset('storage/' . $appSetting->app_logo) }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                @else
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                @endif
                <div x-show="!sidebarMinimized" x-transition.duration.300ms class="whitespace-nowrap overflow-hidden">
                    <h1 class="text-lg font-bold tracking-tight">{{ isset($appSetting) ? $appSetting->app_name : 'WeTest' }}</h1>
                    <p class="text-[11px] text-slate-400 -mt-0.5">Computer Based Test</p>
                </div>
                <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-slate-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                @yield('sidebar')
            </nav>

            {{-- User Info --}}
            <div class="px-4 py-4 border-t border-white/10 overflow-hidden transition-all duration-300">
                <div class="flex items-center gap-3" :class="sidebarMinimized ? 'justify-center' : ''">
                    <div class="w-9 h-9 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center text-sm font-bold shadow-lg shrink-0">
                        {{ strtoupper(substr(auth()->check() ? auth()->user()->name : session('siswa_name', 'S'), 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0" x-show="!sidebarMinimized" x-transition.duration.300ms>
                        <p class="text-sm font-semibold truncate">{{ auth()->check() ? auth()->user()->name : session('siswa_name', 'Siswa') }}</p>
                        <p class="text-[11px] text-slate-400 truncate capitalize">{{ auth()->check() ? auth()->user()->role : 'Siswa' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-slate-200/80 px-4 sm:px-6 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <button @click="sidebarMinimized = !sidebarMinimized" class="hidden lg:block p-2 rounded-lg hover:bg-slate-100 text-slate-500 transition" title="Toggle Sidebar">
                            <svg x-show="!sidebarMinimized" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                            <svg x-show="sidebarMinimized" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                        </button>
                        <h2 class="text-lg font-bold text-slate-800">@yield('page-title', 'Dashboard')</h2>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-center gap-2 animate-fade-in">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Alpine.js CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function confirmAction(formId, title, text, icon) {
            Swal.fire({
                title: title || 'Apakah Anda yakin?',
                text: text || 'Data yang dihapus tidak bisa dikembalikan.',
                icon: icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, lanjutkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>

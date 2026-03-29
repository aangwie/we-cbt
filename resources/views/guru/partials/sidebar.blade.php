{{-- Reusable Guru Sidebar --}}
<a href="{{ route('guru.dashboard') }}" title="Dashboard" :class="sidebarMinimized ? 'justify-center px-2' : 'gap-3 px-4'" class="flex items-center py-2.5 rounded-xl {{ request()->routeIs('guru.dashboard') ? 'bg-white/10 text-white font-medium' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} text-sm transition">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
    <span x-show="!sidebarMinimized" x-transition.opacity class="whitespace-nowrap">Dashboard</span>
</a>
<a href="{{ route('guru.soal.index') }}" title="Kelola Soal" :class="sidebarMinimized ? 'justify-center px-2' : 'gap-3 px-4'" class="flex items-center py-2.5 rounded-xl {{ request()->routeIs('guru.soal.*') ? 'bg-white/10 text-white font-medium' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} text-sm transition">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span x-show="!sidebarMinimized" x-transition.opacity class="whitespace-nowrap">Kelola Soal</span>
</a>
<a href="{{ route('guru.ujian.index') }}" title="Kelola Ujian" :class="sidebarMinimized ? 'justify-center px-2' : 'gap-3 px-4'" class="flex items-center py-2.5 rounded-xl {{ request()->routeIs('guru.ujian.*') ? 'bg-white/10 text-white font-medium' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} text-sm transition">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    <span x-show="!sidebarMinimized" x-transition.opacity class="whitespace-nowrap">Kelola Ujian</span>
</a>
<a href="{{ route('guru.hasil.index') }}" title="Hasil Ujian" :class="sidebarMinimized ? 'justify-center px-2' : 'gap-3 px-4'" class="flex items-center py-2.5 rounded-xl {{ request()->routeIs('guru.hasil.*') ? 'bg-white/10 text-white font-medium' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} text-sm transition">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
    <span x-show="!sidebarMinimized" x-transition.opacity class="whitespace-nowrap">Hasil Ujian</span>
</a>

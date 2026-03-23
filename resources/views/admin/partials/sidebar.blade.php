{{-- Reusable Admin Sidebar --}}
<a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white font-medium' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} text-sm transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
    Dashboard
</a>
<a href="{{ route('admin.guru.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('admin.guru.*') ? 'bg-white/10 text-white font-medium' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} text-sm transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    Kelola Guru
</a>
<a href="{{ route('admin.siswa.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('admin.siswa.*') ? 'bg-white/10 text-white font-medium' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} text-sm transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    Kelola Siswa
</a>
<a href="{{ route('admin.ujian.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('admin.ujian.*') ? 'bg-white/10 text-white font-medium' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} text-sm transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    Kelola Ujian
</a>
<a href="{{ route('admin.soal.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('admin.soal.*') ? 'bg-white/10 text-white font-medium' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} text-sm transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    Kelola Soal
</a>
<a href="{{ route('admin.hasil.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('admin.hasil.*') ? 'bg-white/10 text-white font-medium' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} text-sm transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
    Kelola Hasil
</a>

<div class="my-4 border-t border-white/10"></div>

<a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('admin.settings.*') ? 'bg-white/10 text-white font-medium' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} text-sm transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
    Pengaturan
</a>

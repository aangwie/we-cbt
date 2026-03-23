{{-- Reusable Siswa Sidebar --}}
<a href="{{ route('siswa.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('siswa.dashboard') ? 'bg-white/10 text-white font-medium' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} text-sm transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
    Dashboard
</a>
<a href="{{ route('siswa.konfirmasi') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('siswa.konfirmasi') ? 'bg-white/10 text-white font-medium' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} text-sm transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
    Mulai Ujian
</a>

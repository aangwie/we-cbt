@extends('layouts.app')
@section('title', 'Dashboard Siswa - WeTest')
@section('page-title', 'Dashboard')
@section('sidebar') @include('siswa.partials.sidebar') @endsection

@section('content')
    {{-- Student Info Card --}}
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 rounded-2xl p-6 text-white mb-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2240%22%20height%3D%2240%22%20viewBox%3D%220%200%2040%2040%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M0%2020h40v20H0z%22%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.03%22%2F%3E%3C%2Fsvg%3E')]"></div>
        <div class="relative">
            <h3 class="text-xl font-bold mb-3">Selamat Datang, {{ $siswa->name }}! 👋</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3">
                    <p class="text-blue-100 text-xs">NISN</p>
                    <p class="font-semibold mt-0.5">{{ $siswa->nisn }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3">
                    <p class="text-blue-100 text-xs">Kelas</p>
                    <p class="font-semibold mt-0.5">{{ $siswa->kelas }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3">
                    <p class="text-blue-100 text-xs">Jenis Kelamin</p>
                    <p class="font-semibold mt-0.5">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3">
                    <p class="text-blue-100 text-xs">Tanggal Lahir</p>
                    <p class="font-semibold mt-0.5">{{ $siswa->tanggal_lahir->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Action --}}
    <div class="mb-8">
        <a href="{{ route('siswa.konfirmasi') }}" class="inline-flex items-center gap-3 px-6 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition shadow-lg shadow-emerald-500/25 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            Mulai / Lanjutkan Ujian
        </a>
    </div>

    {{-- ═══ Ujian Sedang Dikerjakan ═══ --}}
    @if($activeSessions->count() > 0)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-2.5 h-2.5 bg-amber-500 rounded-full animate-pulse"></div>
            <h3 class="text-base font-bold text-slate-800">Ujian Belum Selesai</h3>
            <span class="text-xs text-slate-400 ml-auto">Masukkan token untuk melanjutkan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Progress</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Sisa Waktu</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($activeSessions as $i => $sesi)
                    @php
                        $percent = $sesi->total_soal > 0 ? round(($sesi->answered_count / $sesi->total_soal) * 100) : 0;
                        $remaining = $sesi->remaining_seconds;
                        $mins = floor($remaining / 60);
                        $secs = $remaining % 60;
                    @endphp
                    <tr class="hover:bg-amber-50/30 transition">
                        <td class="px-6 py-3.5 text-slate-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-3.5 font-medium text-slate-800">{{ $sesi->ujian->judul }}</td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-2">
                                <div style="flex:1; background:#e2e8f0; border-radius:9999px; height:6px; overflow:hidden; min-width:60px;">
                                    <div style="height:100%; border-radius:9999px; width:{{ $percent }}%; background:{{ $percent >= 80 ? '#10b981' : ($percent >= 50 ? '#3b82f6' : '#f59e0b') }}; transition:width 0.5s;"></div>
                                </div>
                                <span class="text-xs font-semibold text-slate-500">{{ $sesi->answered_count }}/{{ $sesi->total_soal }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            @if($sesi->is_expired)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Waktu Habis
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $mins }}m {{ $secs }}s
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5">
                            @if($sesi->is_expired)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-red-600">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                    Waktu Habis
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-600">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                    Belum Selesai
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5">
                            @if(!$sesi->is_expired)
                                <a href="{{ route('siswa.konfirmasi') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-semibold rounded-lg hover:from-amber-600 hover:to-orange-600 transition shadow-sm" title="Masukkan token untuk lanjutkan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Lanjutkan
                                </a>
                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ═══ Riwayat Ujian Selesai ═══ --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></div>
            <h3 class="text-base font-bold text-slate-800">Riwayat Ujian Selesai</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Benar</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nilai</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($hasilUjians as $i => $hasil)
                    <tr class="hover:bg-emerald-50/30 transition">
                        <td class="px-6 py-3.5 text-slate-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-3.5 font-medium text-slate-800">{{ $hasil->ujian->judul }}</td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $hasil->jumlah_benar }}</td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $hasil->nilai >= 70 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ $hasil->nilai }}</span>
                        </td>
                        <td class="px-6 py-3.5 text-slate-600 text-xs">{{ $hasil->tgl_selesai->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Selesai
                            </span>
                        </td>
                        <td class="px-6 py-3.5">
                            <a href="{{ route('siswa.hasil', $hasil) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Lihat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-slate-400">Belum ada riwayat ujian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

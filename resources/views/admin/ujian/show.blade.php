@extends('layouts.app')
@section('title', 'Detail Ujian - WeTest')
@section('page-title', 'Detail Ujian')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
    {{-- Ujian Info --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <p class="text-xs text-slate-500 mb-1">Judul Ujian</p>
                <p class="text-sm font-bold text-slate-800">{{ $ujian->judul }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-1">Guru Pengampu</p>
                <p class="text-sm font-medium text-slate-700">{{ $ujian->guru->name }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-1">Token</p>
                <code class="px-2.5 py-1 bg-slate-100 rounded-lg text-sm font-mono font-bold text-slate-700">{{ $ujian->token }}</code>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-1">Status</p>
                @if($ujian->is_active)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Aktif</span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-semibold"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>Nonaktif</span>
                @endif
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-2 text-sm text-slate-500">
            <span>{{ $totalSoal }} soal</span>
            <span>•</span>
            <span>{{ $ujian->durasi }} menit</span>
            <span>•</span>
            <span>{{ $activeSiswa->count() }} siswa sedang mengerjakan</span>
            <span>•</span>
            <span>{{ $ujian->hasilUjians->count() }} siswa selesai</span>
        </div>
    </div>

    {{-- Live Monitor: Siswa Sedang Mengerjakan --}}
    @if($activeSiswa->count() > 0)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse"></div>
                <h3 class="text-base font-bold text-slate-800">Siswa Sedang Mengerjakan</h3>
            </div>
            <span class="text-xs text-slate-400">Auto-refresh halaman untuk update terbaru</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Dijawab</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Belum Dijawab</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Progress</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aktivitas Terakhir</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($activeSiswa as $i => $progress)
                    @php
                        $answered = $progress->answered_count;
                        $remaining = $totalSoal - $answered;
                        $percent = $totalSoal > 0 ? round(($answered / $totalSoal) * 100) : 0;
                    @endphp
                    <tr class="hover:bg-red-50/30 transition">
                        <td class="px-6 py-3.5 text-slate-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-3.5 font-medium text-slate-800">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-400 to-orange-500 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($progress->siswa->name, 0, 1)) }}
                                </div>
                                {{ $progress->siswa->name }}
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">{{ $answered }} / {{ $totalSoal }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $remaining > 0 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">{{ $remaining }} soal</span>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-2">
                                <div style="flex:1; background:#e2e8f0; border-radius:9999px; height:8px; overflow:hidden; min-width:80px;">
                                    <div style="height:100%; border-radius:9999px; width:{{ $percent }}%; background:{{ $percent >= 80 ? '#10b981' : ($percent >= 50 ? '#3b82f6' : '#f59e0b') }}; transition:width 0.5s;"></div>
                                </div>
                                <span class="text-xs font-semibold text-slate-600" style="min-width:35px;">{{ $percent }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 text-xs text-slate-500">
                            {{ \Carbon\Carbon::parse($progress->last_activity)->diffForHumans() }}
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                                Sedang Mengerjakan
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Hasil Ujian (Selesai) --}}
    @if($ujian->hasilUjians->count() > 0)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></div>
            <h3 class="text-base font-bold text-slate-800">Hasil Ujian Siswa (Selesai)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Siswa</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Benar</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nilai</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Selesai</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($ujian->hasilUjians as $i => $hasil)
                    <tr class="hover:bg-emerald-50/30 transition">
                        <td class="px-6 py-3.5 text-slate-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-3.5 font-medium text-slate-800">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($hasil->siswa->name, 0, 1)) }}
                                </div>
                                {{ $hasil->siswa->name }}
                            </div>
                        </td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $hasil->jumlah_benar }} / {{ $totalSoal }}</td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $hasil->nilai >= 70 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ $hasil->nilai }}</span>
                        </td>
                        <td class="px-6 py-3.5 text-slate-600 text-xs">@formatDate($hasil->tgl_selesai)</td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Selesai
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Empty State --}}
    @if($activeSiswa->count() === 0 && $ujian->hasilUjians->count() === 0)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 text-center">
        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <p class="text-slate-500 text-sm">Belum ada siswa yang mengerjakan ujian ini.</p>
    </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('admin.ujian.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>
@endsection

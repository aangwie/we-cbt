@extends('layouts.app')
@section('title', 'Hasil Ujian - WeTest')
@section('page-title', 'Hasil Ujian Siswa')
@section('sidebar') @include('guru.partials.sidebar') @endsection

@section('content')
    @forelse($ujians as $ujian)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">{{ $ujian->judul }}</h3>
            <p class="text-xs text-slate-500 mt-0.5">{{ $ujian->soals_count }} soal • {{ $ujian->hasilUjians->count() }} siswa mengerjakan</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Benar</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nilai</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ujian->hasilUjians as $i => $hasil)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-3.5 text-slate-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-3.5 font-medium text-slate-800">{{ $hasil->siswa->name }}</td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $hasil->jumlah_benar }} / {{ $ujian->soals_count }}</td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $hasil->nilai >= 70 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ $hasil->nilai }}</span>
                        </td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $hasil->tgl_selesai->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-6 text-center text-slate-400">Belum ada siswa yang mengerjakan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 text-center text-slate-400">
        Belum ada ujian yang ditugaskan kepada Anda.
    </div>
    @endforelse
@endsection

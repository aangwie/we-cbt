@extends('layouts.app')
@section('title', 'Kelola Siswa - WeTest')
@section('page-title', 'Kelola Siswa')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-slate-500">Total: <span class="font-semibold text-slate-700">{{ $siswas->count() }}</span> siswa</p>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.siswa.import.form') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-50 text-emerald-600 text-sm font-semibold rounded-xl hover:bg-emerald-100 transition border border-emerald-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import Excel
            </a>
            <a href="{{ route('admin.siswa.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Siswa
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table id="siswaTable" class="w-full text-sm">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">NISN</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kelas</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">JK</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tgl Lahir</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswas as $i => $siswa)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-3.5 text-slate-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-3.5 font-medium text-slate-800">{{ $siswa->name }}</td>
                        <td class="px-6 py-3.5"><code class="text-xs font-mono text-slate-600">{{ $siswa->nisn }}</code></td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $siswa->kelas }}</td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-semibold {{ $siswa->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">{{ $siswa->jenis_kelamin }}</span>
                        </td>
                        <td class="px-6 py-3.5 text-slate-600">@formatDate($siswa->tanggal_lahir, 'd F Y')</td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.siswa.edit', $siswa) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.siswa.destroy', $siswa) }}" method="POST" onsubmit="return confirm('Hapus siswa ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-slate-400">Belum ada siswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3/dist/style.css" rel="stylesheet" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3/dist/umd/simple-datatables.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (document.getElementById("siswaTable")) {
            new simpleDatatables.DataTable("#siswaTable", {
                searchable: true,
                perPage: 10,
                labels: {
                    placeholder: "Cari data siswa...",
                    perPage: "data per hal",
                    noRows: "Tidak ada data siswa ditemukan",
                    info: "Menampilkan {start} - {end} dari {rows} siswa"
                }
            });
        }
    });
</script>
<style>
    /* Styling adjustments for Tailwind integration */
    .dataTable-wrapper { font-family: inherit; }
    .dataTable-top { padding-bottom: 1rem; }
    .dataTable-bottom { padding-top: 1rem; border-top: 1px solid #f1f5f9; }
    .dataTable-input { border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.5rem 1rem; outline: none; }
    .dataTable-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1); }
    .dataTable-selector { border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.25rem 2rem 0.25rem 0.5rem; }
    .dataTable-pagination li a { border: border-radius: 0.25rem; }
</style>
@endpush

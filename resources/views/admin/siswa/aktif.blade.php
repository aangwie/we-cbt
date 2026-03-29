@extends('layouts.app')
@section('title', 'Siswa Aktif - WeTest')
@section('page-title', 'Siswa Sedang Aktif')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-slate-500">Total: <span class="font-semibold text-emerald-600">{{ $activeSiswas->count() }}</span> siswa sedang online</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table id="siswaAktifTable" class="w-full text-sm">
                <thead class="bg-emerald-50/80">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-emerald-700 uppercase tracking-wider">#</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Nama</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-emerald-700 uppercase tracking-wider">NISN</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Kelas</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Status Ujian</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-emerald-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($activeSiswas as $i => $siswa)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-3.5 text-slate-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-3.5 font-medium text-slate-800">{{ $siswa->name }}</td>
                        <td class="px-6 py-3.5"><code class="text-xs font-mono text-slate-600">{{ $siswa->nisn }}</code></td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $siswa->kelas }}</td>
                        <td class="px-6 py-3.5">
                            @if($siswa->sesiUjians()->exists())
                                <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-semibold bg-amber-100 text-amber-700">Mengerjakan Ujian</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-semibold bg-emerald-100 text-emerald-700">Idle (Tidak Ujian)</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5">
                            <form id="kick-form-{{ $siswa->id }}" action="{{ route('admin.siswa.kick', $siswa) }}" method="POST" class="hidden">
                                @csrf
                            </form>
                            <button type="button" onclick="confirmKick('{{ $siswa->id }}', '{{ addslashes($siswa->name) }}')" class="inline-flex relative z-10 cursor-pointer items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 text-xs font-semibold rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Reset Sesi
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada siswa yang sedang aktif/login.</td></tr>
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
        if (document.getElementById("siswaAktifTable")) {
            new simpleDatatables.DataTable("#siswaAktifTable", {
                searchable: true,
                perPage: 10,
                labels: {
                    placeholder: "Cari data siswa aktif...",
                    perPage: "data per hal",
                    noRows: "Tidak ada siswa aktif ditemukan",
                    info: "Menampilkan {start} - {end} dari {rows} siswa aktif"
                }
            });
        }
    });

    window.confirmKick = function(siswaId, siswaName) {
        Swal.fire({
            title: 'Kick Siswa?',
            html: `Apakah Anda yakin ingin mengeluarkan <strong>${siswaName}</strong> dari sesinya? Mereka akan langsung ter-logout pada tindakan berikutnya.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Reset Sekarang!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('kick-form-' + siswaId).submit();
            }
        });
    };
</script>
<style>
    /* Styling adjustments for Tailwind integration */
    .dataTable-wrapper { font-family: inherit; }
    .dataTable-top { padding-bottom: 1rem; }
    .dataTable-bottom { padding-top: 1rem; border-top: 1px solid #f1f5f9; }
    .dataTable-input { border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.5rem 1rem; outline: none; }
    .dataTable-input:focus { border-color: #10b981; box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.1); }
    .dataTable-selector { border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.25rem 2rem 0.25rem 0.5rem; }
    .dataTable-pagination li a { border: border-radius: 0.25rem; }
</style>
@endpush

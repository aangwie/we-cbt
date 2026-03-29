@extends('layouts.app')
@section('title', 'Klasifikasi Soal - WeTest')
@section('page-title', 'Klasifikasi Bank Soal')
@section('sidebar') @include('guru.partials.sidebar') @endsection

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('guru.soal.index') }}" class="p-1.5 bg-white border border-slate-200 rounded-lg text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h2 class="text-xl font-bold text-slate-800">{{ $mapel->nama_mapel }}</h2>
            <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-md border border-blue-200">Kelas {{ $kelas }}</span>
        </div>
        <p class="text-sm text-slate-500 ml-9">Kelola kategori / klasifikasi soal untuk mata pelajaran ini.</p>
    </div>
    
    <div class="flex items-center gap-2">
        <button onclick="openCreateKlasifikasi()" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Klasifikasi
        </button>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50/80">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul Paket Soal</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Ditambahkan Pada</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Soal</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pakets as $i => $paket)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 text-slate-500">{{ $i + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800">{{ $paket->judul }}</div>
                        <div class="text-xs text-slate-400 mt-0.5 max-w-xs truncate" title="{{ $paket->deskripsi }}">{{ $paket->deskripsi ?: 'Tidak ada deskripsi' }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-500">
                        {{ $paket->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-semibold ring-1 ring-slate-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            {{ $paket->soals_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" onclick="openEditKlasifikasi({{ $paket->id }}, '{{ addslashes($paket->judul) }}', '{{ addslashes($paket->deskripsi) }}')" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Paket">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form id="delete-paket-{{ $paket->id }}" action="{{ route('guru.soal.paket.destroy', $paket->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmAction('delete-paket-{{ $paket->id }}', 'Hapus Paket Soal?', 'Peringatan: Seluruh pertanyaan dalam paket ini akan terhapus secara permanen.')" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus Paket">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            <a href="{{ route('guru.soal.paket', $paket->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 text-blue-600 text-xs font-semibold rounded-lg hover:bg-blue-50 hover:border-blue-200 transition shadow-sm">
                                Buka
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada Klasifikasi/Paket Soal yang didaftarkan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<form id="form-create-klasifikasi" action="{{ route('guru.soal.paket.store') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="mapel_id" value="{{ $mapel->id }}">
    <input type="hidden" name="kelas" value="{{ $kelas }}">
    <input type="hidden" name="judul" id="swal-create-judul">
    <input type="hidden" name="deskripsi" id="swal-create-deskripsi">
</form>

<form id="form-edit-klasifikasi" method="POST" style="display:none;">
    @csrf @method('PUT')
    <input type="hidden" name="judul" id="swal-edit-judul">
    <input type="hidden" name="deskripsi" id="swal-edit-deskripsi">
</form>
@endsection

@push('scripts')
<script>
function openCreateKlasifikasi() {
    Swal.fire({
        title: 'Buat Klasifikasi',
        width: 540,
        html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Klasifikasi <span class="text-red-500">*</span></label>
                    <input id="swal-input-judul" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-blue-500 outline-none" placeholder="Contoh: Soal Matematika Semester Ganjil">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi <span class="text-slate-400 font-normal text-xs ml-1">(Opsional)</span></label>
                    <textarea id="swal-input-deskripsi" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-blue-500 outline-none resize-none" rows="3" placeholder="Tambahkan keterangan singkat..."></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Simpan Klasifikasi',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
        customClass: { popup: 'rounded-2xl', htmlContainer: '!px-6 !overflow-visible' },
        preConfirm: () => {
            const judul = document.getElementById('swal-input-judul').value;
            if (!judul.trim()) { Swal.showValidationMessage('Judul Klasifikasi wajib diisi!'); return false; }
            return { judul, deskripsi: document.getElementById('swal-input-deskripsi').value };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('swal-create-judul').value = result.value.judul;
            document.getElementById('swal-create-deskripsi').value = result.value.deskripsi;
            document.getElementById('form-create-klasifikasi').submit();
        }
    });
}

function openEditKlasifikasi(id, judul, deskripsi) {
    Swal.fire({
        title: 'Edit Klasifikasi',
        width: 540,
        html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Klasifikasi <span class="text-red-500">*</span></label>
                    <input id="swal-edit-input-judul" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-blue-500 outline-none" value="${judul}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi <span class="text-slate-400 font-normal text-xs ml-1">(Opsional)</span></label>
                    <textarea id="swal-edit-input-deskripsi" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-blue-500 outline-none resize-none" rows="3">${deskripsi || ''}</textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Simpan Perubahan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
        customClass: { popup: 'rounded-2xl', htmlContainer: '!px-6 !overflow-visible' },
        preConfirm: () => {
            const judul = document.getElementById('swal-edit-input-judul').value;
            if (!judul.trim()) { Swal.showValidationMessage('Judul Klasifikasi wajib diisi!'); return false; }
            return { judul, deskripsi: document.getElementById('swal-edit-input-deskripsi').value };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('form-edit-klasifikasi');
            form.action = `{{ url('guru/soal/paket') }}/${id}`;
            document.getElementById('swal-edit-judul').value = result.value.judul;
            document.getElementById('swal-edit-deskripsi').value = result.value.deskripsi;
            form.submit();
        }
    });
}
</script>
@endpush

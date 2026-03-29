@extends('layouts.app')
@section('title', 'Kelola Soal - WeTest')
@section('page-title', 'Kelola Soal (Bank Soal)')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <p class="text-sm text-slate-500">Daftar seluruh Mata Pelajaran dan Kelas yang tersedia di Bank Soal.</p>
    <button onclick="openBuatBankSoal()" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Buat Bank Soal
    </button>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50/80">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kelas</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah Paket</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Soal</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($mapelKelas as $i => $item)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 text-slate-500">{{ $i + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <span class="font-bold text-slate-800">{{ $item->mapel->nama_mapel }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            Kelas {{ $item->kelas }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-600 text-xs font-semibold ring-1 ring-indigo-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            {{ $item->paket_count }} Paket
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-semibold ring-1 ring-slate-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            {{ $item->soal_count }} Soal
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.soal.detail', ['mapel_id' => $item->mapel_id, 'kelas' => $item->kelas]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 text-blue-600 text-xs font-semibold rounded-lg hover:bg-blue-50 hover:border-blue-200 transition shadow-sm">
                            Kelola Paket
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada Bank Soal. Klik tombol "Buat Bank Soal" untuk memulai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<form id="form-buat-banksoal" action="{{ route('admin.soal.paket.store') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="mapel_id" id="swal-mapel-id">
    <input type="hidden" name="kelas" id="swal-kelas">
    <input type="hidden" name="judul" id="swal-judul">
    <input type="hidden" name="deskripsi" id="swal-deskripsi">
</form>
@endsection

@push('scripts')
<script>
function openBuatBankSoal() {
    Swal.fire({
        title: 'Buat Bank Soal',
        width: 540,
        html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select id="swal-input-mapel" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-blue-500 outline-none">
                        <option value="">— Pilih Mata Pelajaran —</option>
                        @foreach($mapels as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kelas <span class="text-red-500">*</span></label>
                    <select id="swal-input-kelas" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-blue-500 outline-none">
                        <option value="">— Pilih Kelas —</option>
                        @foreach($kelasList as $kls)
                        <option value="{{ $kls->nama_kelas }}">{{ $kls->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Klasifikasi <span class="text-red-500">*</span></label>
                    <input id="swal-input-judul" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-blue-500 outline-none" placeholder="Contoh: Soal UTS Semester Ganjil">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi <span class="text-slate-400 font-normal text-xs ml-1">(Opsional)</span></label>
                    <textarea id="swal-input-deskripsi" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-blue-500 outline-none resize-none" rows="3" placeholder="Tambahkan keterangan singkat..."></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Buat Bank Soal',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
        customClass: { popup: 'rounded-2xl', htmlContainer: '!px-6 !overflow-visible' },
        preConfirm: () => {
            const mapel = document.getElementById('swal-input-mapel').value;
            const kelas = document.getElementById('swal-input-kelas').value;
            const judul = document.getElementById('swal-input-judul').value;
            if (!mapel) { Swal.showValidationMessage('Pilih Mata Pelajaran!'); return false; }
            if (!kelas) { Swal.showValidationMessage('Pilih Kelas!'); return false; }
            if (!judul.trim()) { Swal.showValidationMessage('Judul Klasifikasi wajib diisi!'); return false; }
            return { mapel, kelas, judul, deskripsi: document.getElementById('swal-input-deskripsi').value };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('swal-mapel-id').value = result.value.mapel;
            document.getElementById('swal-kelas').value = result.value.kelas;
            document.getElementById('swal-judul').value = result.value.judul;
            document.getElementById('swal-deskripsi').value = result.value.deskripsi;
            document.getElementById('form-buat-banksoal').submit();
        }
    });
}
</script>
@endpush

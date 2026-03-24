@extends('layouts.app')
@section('title', 'Import Siswa - WeTest')
@section('page-title', 'Import Excel Siswa')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
<div class="max-w-2xl" x-data="siswaImportData()">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        
        <div class="mb-6 flex items-start gap-4 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
            <div class="p-2 bg-emerald-100 rounded-lg shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-emerald-800 mb-1">Panduan Import</h3>
                <p class="text-sm text-emerald-700 leading-relaxed">Pastikan file Excel (.xlsx) Anda memiliki kolom: <b>nama, nisn, kelas, jenis_kelamin</b> (L/P), <b>tanggal_lahir</b> (YYYY-MM-DD).</p>
                <div class="mt-3">
                    <a href="{{ route('admin.siswa.template') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-emerald-700 text-xs font-semibold rounded-lg hover:bg-emerald-50 transition border border-emerald-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download Template
                    </a>
                </div>
            </div>
        </div>

        <form @submit.prevent="submitForm">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih File Excel</label>
                <input type="file" x-ref="fileInput" accept=".xlsx,.xls,.csv" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition outline-none border border-slate-200 rounded-xl" :disabled="isUploading">
            </div>
            
            <div class="flex items-center gap-3 pt-5">
                <button type="submit" class="inline-flex items-center justify-center min-w-[120px] px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/25 disabled:opacity-70 disabled:cursor-not-allowed" :disabled="isUploading">
                    <span x-show="!isUploading">Mulai Import</span>
                    <span x-show="isUploading" class="flex items-center gap-2" style="display: none;">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memproses...
                    </span>
                </button>
                <a href="{{ route('admin.siswa.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition" x-show="!isUploading">Kembali</a>
            </div>
        </form>

        {{-- Progress Area --}}
        <div x-show="showProgress" class="mt-8 border-t border-slate-100 pt-6" style="display: none;">
            <div class="mb-2 flex justify-between items-center text-sm">
                <span class="font-semibold text-slate-700" x-text="progressText"></span>
                <span class="font-bold text-blue-600" x-text="progress + '%'"></span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5 mb-6 overflow-hidden">
                <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300 ease-out" :style="'width: ' + progress + '%'"></div>
            </div>

            {{-- Result Area --}}
            <div x-show="isComplete" class="grid grid-cols-2 gap-4 animate-fade-in" style="display: none;">
                <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                    <p class="text-emerald-600 text-sm font-semibold mb-1">Berhasil</p>
                    <p class="text-3xl font-bold text-emerald-700">
                        <span x-text="successCount"></span>
                        <span class="text-sm font-medium text-emerald-600 ml-1">data</span>
                    </p>
                </div>
                <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                    <p class="text-red-600 text-sm font-semibold mb-1">Gagal</p>
                    <p class="text-3xl font-bold text-red-700">
                        <span x-text="failedCount"></span>
                        <span class="text-sm font-medium text-red-600 ml-1">data</span>
                    </p>
                </div>
            </div>

            <div x-show="isComplete && errors.length > 0" class="mt-4 bg-orange-50 rounded-xl p-4 border border-orange-100 animate-fade-in" style="display: none;">
                <h4 class="text-sm font-bold text-orange-800 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Detail Error (Maksimal 10):
                </h4>
                <ul class="list-disc list-inside text-xs text-orange-700 space-y-1">
                    <template x-for="err in errors">
                        <li x-text="err"></li>
                    </template>
                </ul>
            </div>
            
            <div x-show="isComplete" class="mt-6 flex justify-end" style="display: none;">
                <a href="{{ route('admin.siswa.index') }}" class="px-6 py-2.5 bg-slate-800 text-white text-sm font-semibold rounded-xl hover:bg-slate-900 transition flex items-center gap-2">
                    Tutup
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('siswaImportData', () => ({
        isUploading: false,
        showProgress: false,
        isComplete: false,
        progress: 0,
        progressText: 'Mempersiapkan...',
        successCount: 0,
        failedCount: 0,
        errors: [],

        async submitForm() {
            const fileInput = this.$refs.fileInput;
            if (!fileInput.files.length) return;

            const formData = new FormData();
            formData.append('file_excel', fileInput.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            this.isUploading = true;
            this.showProgress = true;
            this.isComplete = false;
            this.progress = 0;
            this.errors = [];
            
            let simInterval = setInterval(() => {
                if (this.progress < 75) {
                    this.progress += Math.floor(Math.random() * 15) + 5;
                    this.progressText = 'Mengunggah dan Membaca File...';
                    if (this.progress > 75) this.progress = 75;
                }
            }, 300);

            try {
                const response = await fetch("{{ route('admin.siswa.import') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                clearInterval(simInterval);

                if (response.ok && result.status === 'success') {
                    this.progress = 100;
                    this.progressText = 'Selesai diproses!';
                    
                    setTimeout(() => {
                        this.isUploading = false;
                        this.isComplete = true;
                        this.successCount = result.success_count;
                        this.failedCount = result.failed_count;
                        this.errors = result.errors || [];
                    }, 500);

                } else {
                    this.handleError(result.message || 'Terjadi kesalahan pada server');
                }
            } catch (error) {
                clearInterval(simInterval);
                this.handleError('Koneksi terputus atau server error.');
            }
        },

        handleError(msg) {
            this.isUploading = false;
            this.progress = 0;
            this.showProgress = false;
            Swal.fire({
                icon: 'error',
                title: 'Import Gagal',
                text: msg,
                customClass: { popup: 'rounded-2xl' }
            });
        }
    }));
});
</script>
@endpush

@extends('layouts.app')
@section('title', 'Ujian - ' . $ujian->judul . ' - WeTest')
@section('page-title', $ujian->judul)
@section('sidebar') @include('siswa.partials.sidebar') @endsection

@section('content')
    <div x-data="examApp()" x-init="startTimer()" class="flex flex-col lg:flex-row gap-6">

        {{-- ═══ LEFT: Question Area ═══ --}}
        <div class="flex-1 min-w-0">
            <form method="POST" action="{{ route('siswa.ujian.submit', $ujian) }}" id="examForm">
                @csrf

                @foreach($soals as $i => $soal)
                <div x-show="currentIndex === {{ $i }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

                    {{-- Question Header --}}
                    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-xl flex items-center justify-center text-sm font-bold shadow-lg shadow-blue-500/20">{{ $i + 1 }}</span>
                            <div>
                                <p class="text-xs text-slate-500">Soal</p>
                                <p class="text-sm font-bold text-slate-800">Nomor {{ $i + 1 }} <span class="text-slate-400 font-normal">dari {{ $soals->count() }}</span></p>
                            </div>
                        </div>
                        {{-- Save indicator --}}
                        <div x-show="savedIndicator === {{ $soal->id }}" x-transition class="flex items-center gap-1 text-emerald-600 text-xs font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Tersimpan
                        </div>
                    </div>

                    {{-- Question Body --}}
                    <div class="p-6">
                        <p class="text-sm text-slate-800 font-medium leading-relaxed mb-4">{{ $soal->teks_soal }}</p>

                        @if($soal->gambar_soal)
                            <div class="mb-5">
                                <img src="{{ asset('storage/' . $soal->gambar_soal) }}" alt="Gambar soal" class="max-h-52 rounded-xl border border-slate-200 shadow-sm">
                            </div>
                        @endif

                        {{-- Options --}}
                        <div class="space-y-2.5">
                            @foreach(['a', 'b', 'c', 'd', 'e'] as $opt)
                            <label class="flex items-start gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all duration-200 group"
                                   :class="answers[{{ $soal->id }}] === '{{ $opt }}' ? 'border-blue-500 bg-blue-50 shadow-sm shadow-blue-500/10' : 'border-slate-200 hover:border-blue-300 hover:bg-blue-50/30'"
                                   @click="selectAnswer({{ $soal->id }}, '{{ $opt }}')">
                                <div class="mt-0.5 w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-all duration-200"
                                     :class="answers[{{ $soal->id }}] === '{{ $opt }}' ? 'border-blue-500 bg-blue-500' : 'border-slate-300 group-hover:border-blue-400'">
                                    <div class="w-2 h-2 rounded-full bg-white transition-transform duration-200"
                                         :class="answers[{{ $soal->id }}] === '{{ $opt }}' ? 'scale-100' : 'scale-0'"></div>
                                </div>
                                <input type="radio" name="jawaban_{{ $soal->id }}" value="{{ $opt }}" x-model="answers[{{ $soal->id }}]" class="sr-only">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold uppercase transition-all duration-200"
                                              :class="answers[{{ $soal->id }}] === '{{ $opt }}' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-100 group-hover:text-blue-600'">{{ $opt }}</span>
                                        <span class="text-sm text-slate-700">{{ $soal->{'pilihan_' . $opt} }}</span>
                                    </div>
                                    @if($soal->{'gambar_' . $opt})
                                        <img src="{{ asset('storage/' . $soal->{'gambar_' . $opt}) }}" alt="Gambar {{ $opt }}" class="mt-2.5 max-h-28 rounded-lg border border-slate-200">
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Navigation Footer with Ragu-Ragu --}}
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <button type="button" @click="prev()" :disabled="currentIndex === 0"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200"
                                :class="currentIndex === 0 ? 'text-slate-300 cursor-not-allowed' : 'text-slate-600 hover:bg-white hover:shadow-sm border border-transparent hover:border-slate-200'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Sebelumnya
                        </button>

                        {{-- Ragu-Ragu Button (CENTER) --}}
                        <button type="button" @click="toggleRagu({{ $soal->id }})"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 border"
                                :class="ragu[{{ $soal->id }}] ? 'bg-amber-100 border-amber-300 text-amber-700 shadow-sm shadow-amber-200/50' : 'bg-slate-100 border-slate-200 text-slate-500 hover:bg-amber-50 hover:border-amber-200 hover:text-amber-600'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            <span x-text="ragu[{{ $soal->id }}] ? 'Ditandai Ragu' : 'Ragu-ragu'"></span>
                        </button>

                        <template x-if="currentIndex < totalSoal - 1">
                            <button type="button" @click="next()"
                                    class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-blue-500/20">
                                Selanjutnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </template>
                        <template x-if="currentIndex === totalSoal - 1">
                            <button type="button" @click="submitExam()"
                                    :disabled="answeredCount < totalSoal"
                                    class="flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-xl transition"
                                    :class="answeredCount < totalSoal ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white hover:from-emerald-700 hover:to-teal-700 shadow-lg shadow-emerald-500/20'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Kumpulkan Ujian
                            </button>
                        </template>
                    </div>
                </div>
                @endforeach
            </form>
        </div>

        {{-- ═══ RIGHT: Sidebar Panel ═══ --}}
        <div class="w-full lg:w-72 shrink-0 space-y-4">

            {{-- Timer Card --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 sticky top-20">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors duration-300"
                         :class="timerDanger ? 'bg-red-100 animate-pulse' : 'bg-blue-100'">
                        <svg class="w-5 h-5 transition-colors duration-300" :class="timerDanger ? 'text-red-600' : 'text-blue-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Sisa Waktu</p>
                        <p class="text-xl font-bold font-mono transition-colors duration-300"
                           :class="timerDanger ? 'text-red-600' : (timerWarning ? 'text-amber-600' : 'text-slate-800')" x-text="timerDisplay">--:--</p>
                    </div>
                </div>
                {{-- Progress Bar --}}
                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500"
                         :class="timerDanger ? 'bg-red-500' : 'bg-blue-500'"
                         :style="'width: ' + answeredPercent + '%'"></div>
                </div>
                <p class="text-xs text-slate-400 mt-1.5" x-text="answeredCount + ' dari ' + totalSoal + ' dijawab'"></p>
            </div>

            {{-- Navigasi Soal --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <h4 class="text-sm font-bold text-slate-800 mb-3">Navigasi Soal</h4>

                {{-- Legend --}}
                <div class="flex flex-wrap gap-x-4 gap-y-1 mb-4 text-[11px] text-slate-500">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-emerald-500 inline-block"></span>Tersimpan</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-amber-400 inline-block"></span>Ragu-ragu</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-slate-200 inline-block"></span>Belum</span>
                </div>

                {{-- Grid --}}
                <div class="grid grid-cols-5 gap-2">
                    @foreach($soals as $i => $soal)
                    <button type="button" @click="goTo({{ $i }})"
                            class="w-full aspect-square rounded-xl flex items-center justify-center text-sm font-bold transition-all duration-200 border-2 hover:scale-105"
                            :class="getNavClass({{ $i }}, {{ $soal->id }})">
                        {{ $i + 1 }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Submit Card --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <button type="button" @click="submitExam()"
                        :disabled="answeredCount < totalSoal"
                        class="w-full py-3 font-semibold rounded-xl transition text-sm flex items-center justify-center gap-2"
                        :class="answeredCount < totalSoal ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white hover:from-emerald-700 hover:to-teal-700 shadow-lg shadow-emerald-500/25'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Kumpulkan Ujian
                </button>
                <p class="text-[11px] text-slate-400 text-center mt-2" x-text="answeredCount < totalSoal ? 'Jawab semua soal untuk mengumpulkan (' + (totalSoal - answeredCount) + ' soal tersisa)' : 'Semua soal sudah dijawab'"></p>
            </div>
        </div>
    </div>

    {{-- Time-Up Overlay --}}
    <div x-show="timeUp" x-transition style="display:none" class="fixed inset-0 z-[60] bg-slate-900/80 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md mx-4 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Waktu Habis!</h3>
            <p class="text-sm text-slate-500 mb-4">Jawaban Anda sedang dikunci dan dikirim untuk dikoreksi...</p>
            <div class="flex items-center justify-center gap-2 text-blue-600">
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                <span class="text-sm font-semibold">Mengumpulkan...</span>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function examApp() {
        return {
            currentIndex: 0,
            totalSoal: {{ $soals->count() }},
            answers: {
                @foreach($soals as $soal)
                {{ $soal->id }}: {!! isset($savedAnswers[$soal->id]) ? "'" . $savedAnswers[$soal->id] . "'" : 'null' !!},
                @endforeach
            },
            ragu: {
                @foreach($soals as $soal)
                {{ $soal->id }}: false,
                @endforeach
            },
            soalIds: [
                @foreach($soals as $soal)
                {{ $soal->id }},
                @endforeach
            ],
            remaining: {{ $remainingSeconds }},
            timerDisplay: '--:--',
            timerDanger: false,
            timerWarning: false,
            timeUp: false,
            savedIndicator: null,
            saveUrl: '{{ route("siswa.ujian.save-answer", $ujian) }}',
            csrfToken: '{{ csrf_token() }}',

            get answeredCount() {
                return Object.values(this.answers).filter(a => a !== null).length;
            },

            get answeredPercent() {
                return this.totalSoal > 0 ? Math.round((this.answeredCount / this.totalSoal) * 100) : 0;
            },

            selectAnswer(soalId, opt) {
                this.answers[soalId] = opt;
                if (!this.ragu[soalId]) {
                    this.autoSave(soalId, opt);
                }
            },

            toggleRagu(soalId) {
                this.ragu[soalId] = !this.ragu[soalId];
                if (!this.ragu[soalId] && this.answers[soalId]) {
                    this.autoSave(soalId, this.answers[soalId]);
                }
            },

            autoSave(soalId, jawaban) {
                fetch(this.saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ soal_id: soalId, jawaban: jawaban })
                }).then(res => {
                    if (res.ok) {
                        this.savedIndicator = soalId;
                        setTimeout(() => { this.savedIndicator = null; }, 1500);
                    }
                }).catch(() => {});
            },

            submitExam() {
                if (this.answeredCount < this.totalSoal) return;
                Swal.fire({
                    title: 'Kumpulkan Ujian?',
                    text: 'Pastikan semua jawaban sudah benar. Jawaban tidak bisa diubah setelah dikumpulkan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, kumpulkan!',
                    cancelButtonText: 'Periksa lagi',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('examForm').submit();
                    }
                });
            },

            next() {
                if (this.currentIndex < this.totalSoal - 1) this.currentIndex++;
            },

            prev() {
                if (this.currentIndex > 0) this.currentIndex--;
            },

            goTo(index) {
                this.currentIndex = index;
            },

            getNavClass(index, soalId) {
                const isActive = this.currentIndex === index;
                const isAnswered = this.answers[soalId] !== null;
                const isRagu = this.ragu[soalId];

                if (isRagu) {
                    return isActive
                        ? 'bg-amber-400 text-white border-amber-500 shadow-md shadow-amber-400/30 scale-110'
                        : 'bg-amber-400 text-white border-amber-300 hover:shadow-md';
                }
                if (isAnswered) {
                    return isActive
                        ? 'bg-emerald-500 text-white border-emerald-600 shadow-md shadow-emerald-500/30 scale-110'
                        : 'bg-emerald-500 text-white border-emerald-400 hover:shadow-md';
                }
                return isActive
                    ? 'bg-slate-300 text-slate-700 border-slate-400 shadow-md scale-110'
                    : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200';
            },

            startTimer() {
                const form = document.getElementById('examForm');
                const self = this;

                const tick = () => {
                    if (self.remaining <= 0) {
                        self.timerDisplay = '00:00';
                        self.timerDanger = true;
                        self.timeUp = true;
                        self.saveAllRaguThenSubmit(form);
                        return;
                    }
                    const m = Math.floor(self.remaining / 60);
                    const s = self.remaining % 60;
                    self.timerDisplay = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                    self.timerDanger = self.remaining <= 60;
                    self.timerWarning = self.remaining <= 300;
                    self.remaining--;
                };
                tick();
                setInterval(tick, 1000);
            },

            async saveAllRaguThenSubmit(form) {
                const promises = [];
                for (const soalId of this.soalIds) {
                    if (this.answers[soalId] && this.ragu[soalId]) {
                        promises.push(
                            fetch(this.saveUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.csrfToken,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ soal_id: soalId, jawaban: this.answers[soalId] })
                            }).catch(() => {})
                        );
                    }
                }
                await Promise.all(promises);
                setTimeout(() => form.submit(), 500);
            }
        };
    }
</script>
@endpush

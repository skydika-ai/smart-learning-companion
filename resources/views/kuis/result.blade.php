<x-app-layout>

    @php
        $totalSoal   = $kuis->soalKuis->count();
        $jumlahBenar = round(($hasil->skor / 100) * $totalSoal);
        $jumlahSalah = $totalSoal - $jumlahBenar;

        $detikTotal = $hasil->waktu_pengerjaan ?? 0;
        $menitFmt   = str_pad(floor($detikTotal / 60), 2, '0', STR_PAD_LEFT);
        $detikFmt   = str_pad($detikTotal % 60, 2, '0', STR_PAD_LEFT);
        $waktuFmt   = "{$menitFmt}:{$detikFmt}";

        $realHistory = \App\Models\HasilKuis::where('user_id', auth()->id())
            ->where('kuis_id', $kuis->id)
            ->latest()->take(5)->get()->reverse()->values();

        $totalSlot  = 5;
        $realCount  = $realHistory->count();
        $padCount   = $totalSlot - $realCount;
        $historyScores = [];
        $historyDates  = [];

        for ($i = 0; $i < $padCount; $i++) {
            $historyScores[] = null;
            $historyDates[]  = null;
        }
        foreach ($realHistory as $item) {
            $historyScores[] = $item->skor;
            $historyDates[]  = \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M');
        }

        $circumference = 2 * M_PI * 54;
        $offset        = $circumference - ($hasil->skor / 100) * $circumference;
        $ringColor     = $hasil->skor >= 80 ? '#22C55E' : ($hasil->skor >= 60 ? '#F59E0B' : '#EF4444');

        $grade = $hasil->skor >= 80 ? ['label' => 'Sangat Baik', 'emoji' => '✨', 'color' => 'text-emerald-500']
               : ($hasil->skor >= 60 ? ['label' => 'Cukup Baik', 'emoji' => '👍', 'color' => 'text-amber-500']
               : ['label' => 'Perlu Belajar Lagi', 'emoji' => '📚', 'color' => 'text-red-500']);
    @endphp

    {{-- CONFETTI (hanya skor ≥ 80) --}}
    @if($hasil->skor >= 80)
    <canvas id="confettiCanvas" class="fixed inset-0 w-full h-full pointer-events-none z-[9999]"></canvas>
    @endif

    <div class="min-h-screen py-8 px-4" style="background: #F0F4FF;">
        <div class="max-w-7xl mx-auto">

            {{-- HEADER --}}
            <div class="mb-8 animate-fadeup" style="animation-delay:0ms">
                <a href="{{ route('materi.show', $kuis->materi) }}"
                   class="inline-flex items-center gap-2 text-[14px] text-slate-400 hover:text-blue-600 transition font-medium group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Materi
                </a>
                <h1 class="text-[46px] font-extrabold text-slate-900 tracking-[-2px] mt-4 leading-none">Hasil Kuis</h1>
                <div class="flex items-center gap-3 mt-4">
                    <div class="w-10 h-10 rounded-2xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h7l5 5v11a1 1 0 01-1 1H7a1 1 0 01-1-1V5a1 1 0 011-1z"/>
                        </svg>
                    </div>
                    <p class="text-[15px] font-semibold text-slate-700">{{ str_replace('Kuis: ', '', $kuis->judul_kuis) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- ===== LEFT ===== --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- SCORE CARD --}}
                    <div class="relative rounded-[36px] overflow-hidden p-8 animate-fadeup" style="animation-delay:80ms; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);">
                        {{-- Glow blobs --}}
                        <div class="absolute top-0 right-0 w-80 h-80 rounded-full opacity-20 blur-3xl pointer-events-none" style="background: radial-gradient(circle, #7c3aed, transparent);"></div>
                        <div class="absolute bottom-0 left-0 w-60 h-60 rounded-full opacity-15 blur-3xl pointer-events-none" style="background: radial-gradient(circle, #2962ff, transparent);"></div>
                        {{-- Stars --}}
                        <div class="absolute inset-0 overflow-hidden pointer-events-none" id="starsContainer"></div>

                        <div class="relative z-10 flex items-center justify-between flex-wrap gap-6">
                            <div>
                                <p class="text-[13px] font-bold uppercase tracking-[3px] text-blue-300 mb-4">Skor Anda</p>
                                <div class="flex items-end gap-1">
                                    <span id="scoreCounter"
                                          class="text-[96px] font-black leading-none tabular-nums"
                                          style="background: linear-gradient(135deg, #fff 30%, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"
                                          data-target="{{ $hasil->skor }}">0</span>
                                    <span class="text-[32px] font-bold text-white/40 mb-4 ml-1">/100</span>
                                </div>
                                <p class="text-[22px] font-bold mt-3 {{ $grade['color'] }}">
                                    {{ $grade['label'] }} {{ $grade['emoji'] }}
                                </p>
                            </div>

                            {{-- Ring --}}
                            <div class="relative w-[148px] h-[148px] shrink-0">
                                <svg width="148" height="148" viewBox="0 0 148 148" class="-rotate-90 absolute inset-0">
                                    <circle cx="74" cy="74" r="54" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="14"/>
                                    <circle id="progressRing" cx="74" cy="74" r="54" fill="none"
                                            stroke="{{ $ringColor }}" stroke-width="14"
                                            stroke-linecap="round"
                                            stroke-dasharray="{{ $circumference }}"
                                            stroke-dashoffset="{{ $circumference }}"
                                            data-offset="{{ $offset }}"
                                            style="transition: stroke-dashoffset 1.4s cubic-bezier(0.4,0,0.2,1); filter: drop-shadow(0 0 8px {{ $ringColor }}88)"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-4xl">🏆</div>
                                        <div class="text-white font-black text-[18px] mt-1" id="ringPct">0%</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bottom bar --}}
                        <div class="relative z-10 mt-8 pt-6 border-t border-white/10 flex items-center gap-6 flex-wrap">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                <span class="text-white/60 text-[13px]">{{ $jumlahBenar }} benar</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-red-400"></div>
                                <span class="text-white/60 text-[13px]">{{ $jumlahSalah }} salah</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                                <span class="text-white/60 text-[13px]">Waktu {{ $waktuFmt }}</span>
                            </div>
                            <div class="ml-auto">
                                <div class="h-2 w-48 bg-white/10 rounded-full overflow-hidden">
                                    <div id="accuracyBar" class="h-full rounded-full transition-all duration-1000"
                                         style="width:0%; background: linear-gradient(90deg, #22c55e, #16a34a);"
                                         data-width="{{ $totalSoal > 0 ? round(($jumlahBenar/$totalSoal)*100) : 0 }}"></div>
                                </div>
                                <p class="text-white/40 text-[11px] mt-1 text-right">Akurasi {{ $totalSoal > 0 ? round(($jumlahBenar/$totalSoal)*100) : 0 }}%</p>
                            </div>
                        </div>
                    </div>

                    {{-- CHART --}}
                    <div class="bg-white rounded-[36px] border border-slate-100 shadow-[0_8px_40px_rgba(15,23,42,0.05)] p-8 animate-fadeup" style="animation-delay:160ms">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-[24px] font-bold text-slate-900">Riwayat Nilai</h2>
                                <p class="text-[13px] text-slate-400 mt-1">Progres kamu mengerjakan kuis ini</p>
                            </div>
                            <div class="flex items-center gap-2 bg-slate-50 border border-slate-100 px-4 py-2 rounded-2xl">
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                <span class="text-[13px] font-semibold text-slate-500">{{ $realCount }}x dikerjakan</span>
                            </div>
                        </div>
                        <div class="relative" style="height:240px">
                            <canvas id="historyChart"></canvas>
                        </div>
                    </div>

                </div>

                {{-- ===== RIGHT ===== --}}
                <div class="lg:col-span-4 flex flex-col gap-5">

                    {{-- STATS --}}
                    <div class="grid grid-cols-2 gap-4 animate-fadeup" style="animation-delay:240ms">

                        {{-- Total --}}
                        <div class="stat-card bg-white rounded-[28px] border border-slate-100 shadow-[0_4px_20px_rgba(15,23,42,0.04)] p-5">
                            <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                                <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-[11px] font-bold uppercase tracking-[2px] text-slate-400">Total Soal</p>
                            <p class="text-[52px] font-black text-slate-900 leading-none mt-1">{{ $totalSoal }}</p>
                        </div>

                        {{-- Benar --}}
                        <div class="stat-card rounded-[28px] border border-emerald-100 p-5" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-100 flex items-center justify-center mb-4">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="text-[11px] font-bold uppercase tracking-[2px] text-emerald-500">Benar</p>
                            <p class="text-[52px] font-black text-emerald-600 leading-none mt-1">{{ $jumlahBenar }}</p>
                        </div>

                        {{-- Salah --}}
                        <div class="stat-card rounded-[28px] border border-red-100 p-5" style="background: linear-gradient(135deg, #fff7f7, #fee2e2);">
                            <div class="w-10 h-10 rounded-2xl bg-red-100 flex items-center justify-center mb-4">
                                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <p class="text-[11px] font-bold uppercase tracking-[2px] text-red-400">Salah</p>
                            <p class="text-[52px] font-black text-red-500 leading-none mt-1">{{ $jumlahSalah }}</p>
                        </div>

                        {{-- Waktu --}}
                        <div class="stat-card bg-white rounded-[28px] border border-slate-100 shadow-[0_4px_20px_rgba(15,23,42,0.04)] p-5">
                            <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center mb-4">
                                <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-[11px] font-bold uppercase tracking-[2px] text-slate-400">Waktu</p>
                            <p class="text-[38px] font-black text-slate-900 leading-none mt-1 tabular-nums">{{ $waktuFmt }}</p>
                        </div>
                    </div>

                    {{-- AKSI --}}
                    <div class="bg-white rounded-[36px] border border-slate-100 shadow-[0_8px_40px_rgba(15,23,42,0.05)] p-6 space-y-3 animate-fadeup" style="animation-delay:320ms">
                        <p class="text-[12px] font-bold uppercase tracking-[2px] text-slate-400 mb-4">Aksi</p>
                        <a href="{{ route('kuis.show', $kuis) }}"
                           class="group relative overflow-hidden w-full h-[62px] rounded-[18px] flex items-center justify-center gap-3
                                  text-white text-[16px] font-bold transition-all duration-300
                                  hover:shadow-[0_16px_40px_rgba(41,98,255,0.4)] hover:-translate-y-0.5 active:scale-[0.97]"
                           style="background: linear-gradient(135deg, #2962ff, #5b8cff);">
                            <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Kerjakan Lagi
                        </a>
                        <button onclick="togglePembahasan()"
                                class="w-full h-[62px] rounded-[18px] bg-slate-50 border border-slate-200
                                       hover:bg-blue-50 hover:border-blue-200 hover:-translate-y-0.5
                                       active:scale-[0.97] transition-all duration-300
                                       text-slate-700 text-[16px] font-semibold flex items-center justify-center gap-3">
                            <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 4h7l5 5v11a1 1 0 01-1 1H7a1 1 0 01-1-1V5a1 1 0 011-1z"/>
                            </svg>
                            Lihat Pembahasan
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PEMBAHASAN --}}
    <div id="modalPembahasan"
        class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
        style="background: rgba(10,12,20,0.65); backdrop-filter: blur(10px);">

        <div class="bg-[#F8FAFF] w-full max-w-4xl rounded-[36px] max-h-[92vh] overflow-hidden shadow-[0_40px_100px_rgba(0,0,0,0.3)] flex flex-col"
            id="modalBox" style="transform:translateY(20px);opacity:0;transition:all 0.3s cubic-bezier(0.4,0,0.2,1)">

            {{-- HEADER --}}
            <div class="bg-white px-8 py-6 border-b border-slate-100 shrink-0">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <h2 class="text-[22px] font-bold text-slate-900">Pembahasan Jawaban</h2>

                        {{-- Mini stats --}}
                        <div class="flex items-center gap-4 mt-3">
                            <div class="flex items-center gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-400"></div>
                                <span class="text-[13px] font-semibold text-slate-500">{{ $jumlahBenar }} benar</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                                <span class="text-[13px] font-semibold text-slate-500">{{ $jumlahSalah }} salah</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-slate-300"></div>
                                <span class="text-[13px] font-semibold text-slate-500">{{ $totalSoal }} soal</span>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        <div class="mt-3 h-2 bg-slate-100 rounded-full overflow-hidden w-full max-w-xs">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-500 transition-all duration-700"
                                style="width: {{ $totalSoal > 0 ? round(($jumlahBenar/$totalSoal)*100) : 0 }}%"></div>
                        </div>
                    </div>

                    <button onclick="togglePembahasan()"
                            class="w-11 h-11 rounded-full bg-slate-100 hover:bg-slate-200 transition-all flex items-center justify-center text-slate-500 text-xl font-bold shrink-0 hover:rotate-90 duration-300">
                        ×
                    </button>
                </div>

                {{-- Filter tabs --}}
                <div class="flex gap-2 mt-5">
                    <button onclick="filterSoal('all')" id="tab-all"
                            class="tab-btn px-4 py-2 rounded-full text-[13px] font-bold transition-all bg-slate-900 text-white">
                        Semua ({{ $totalSoal }})
                    </button>
                    <button onclick="filterSoal('benar')" id="tab-benar"
                            class="tab-btn px-4 py-2 rounded-full text-[13px] font-bold transition-all bg-slate-100 text-slate-500 hover:bg-emerald-50 hover:text-emerald-600">
                        ✓ Benar ({{ $jumlahBenar }})
                    </button>
                    <button onclick="filterSoal('salah')" id="tab-salah"
                            class="tab-btn px-4 py-2 rounded-full text-[13px] font-bold transition-all bg-slate-100 text-slate-500 hover:bg-red-50 hover:text-red-500">
                        ✗ Salah ({{ $jumlahSalah }})
                    </button>
                </div>
            </div>

            {{-- BODY --}}
            <div class="p-6 overflow-y-auto flex-1 space-y-4">
                @foreach($kuis->soalKuis as $index => $soal)
                    @php
                        $rawJawabanUser = $hasil->jawaban_user[(string)$soal->id] ?? null;
                        $jawabanUser    = !empty($rawJawabanUser)
                            ? strtoupper(trim(str_replace('.', '', $rawJawabanUser))) : null;
                        $jawabanBenar   = strtoupper(trim(str_replace('.', '', $soal->jawaban_benar)));
                        $benar          = $jawabanUser === $jawabanBenar;
                        $opsiMap        = [
                            'A' => $soal->opsi_a ?? '',
                            'B' => $soal->opsi_b ?? '',
                            'C' => $soal->opsi_c ?? '',
                            'D' => $soal->opsi_d ?? '',
                        ];
                    @endphp

                    <div class="soal-item rounded-[24px] border overflow-hidden transition-all"
                        data-status="{{ $benar ? 'benar' : 'salah' }}"
                        style="{{ $benar ? 'border-color:#d1fae5; background:#ffffff;' : 'border-color:#fee2e2; background:#ffffff;' }}">

                        {{-- Soal header --}}
                        <div class="px-6 py-5 flex items-start justify-between gap-4"
                            style="{{ $benar ? 'background: linear-gradient(135deg, #f0fdf4, #f8fff9);' : 'background: linear-gradient(135deg, #fff7f7, #fff1f1);' }}">
                            <div class="flex items-start gap-3 flex-1">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-[13px] shrink-0 mt-0.5
                                    {{ $benar ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                                    {{ $index + 1 }}
                                </span>
                                <h3 class="text-[15px] font-bold text-slate-800 leading-snug pt-1">{{ $soal->pertanyaan }}</h3>
                            </div>
                            <span class="shrink-0 px-3 py-1.5 rounded-full text-[12px] font-bold
                                {{ $benar ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                                {{ $benar ? '✓ Benar' : '✗ Salah' }}
                            </span>
                        </div>

                        {{-- Semua opsi --}}
                        <div class="px-6 py-4 space-y-2">
                            @foreach($opsiMap as $key => $value)
                                @php
                                    $isBenar = $key === $jawabanBenar;
                                    $isDipilih = $key === $jawabanUser;
                                    $isSalahDipilih = $isDipilih && !$benar;
                                @endphp
                                <div class="flex items-center gap-3 px-4 py-3 rounded-2xl border transition-all
                                    @if($isBenar) border-emerald-200 bg-emerald-50
                                    @elseif($isSalahDipilih) border-red-200 bg-red-50
                                    @else border-slate-100 bg-slate-50/50
                                    @endif">

                                    {{-- Badge opsi --}}
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-[14px] shrink-0
                                        @if($isBenar) bg-emerald-500 text-white
                                        @elseif($isSalahDipilih) bg-red-400 text-white
                                        @else bg-white border border-slate-200 text-slate-400
                                        @endif">
                                        {{ $key }}
                                    </div>

                                    <p class="text-[14px] leading-relaxed flex-1
                                        @if($isBenar) text-emerald-800 font-semibold
                                        @elseif($isSalahDipilih) text-red-700 font-semibold
                                        @else text-slate-500
                                        @endif">
                                        {{ $value }}
                                    </p>

                                    {{-- Icon status --}}
                                    @if($isBenar)
                                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @elseif($isSalahDipilih)
                                        <svg class="w-5 h-5 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    @endif
                                </div>
                            @endforeach

                            {{-- Penjelasan AI --}}
                            @if(!empty($soal->penjelasan))
                                <div class="mt-3 flex gap-3 bg-blue-50 border border-blue-100 rounded-2xl p-4">
                                    <span class="text-lg shrink-0">📘</span>
                                    <div>
                                        <p class="text-blue-600 font-bold text-[11px] uppercase tracking-[2px] mb-1">Penjelasan AI</p>
                                        <p class="text-slate-600 text-[14px] leading-relaxed">{{ $soal->penjelasan }}</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Soal tidak dijawab --}}
                            @if(empty($jawabanUser))
                                <div class="flex items-center gap-2 mt-2 px-4 py-3 bg-amber-50 border border-amber-100 rounded-2xl">
                                    <span class="text-amber-500">⚠️</span>
                                    <p class="text-amber-700 text-[13px] font-semibold">Soal ini tidak dijawab</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
function togglePembahasan() {
    const modal    = document.getElementById('modalPembahasan');
    const box      = document.getElementById('modalBox');
    const isHidden = modal.classList.contains('hidden');

    if (isHidden) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            box.style.transform = 'translateY(0)';
            box.style.opacity   = '1';
        }, 10);
    } else {
        box.style.transform = 'translateY(20px)';
        box.style.opacity   = '0';
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 280);
    }
}

function filterSoal(filter) {
    // Update tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-slate-900', 'text-white', 'bg-emerald-500', 'bg-red-400');
        btn.classList.add('bg-slate-100', 'text-slate-500');
    });

    const activeTab = document.getElementById('tab-' + filter);
    activeTab.classList.remove('bg-slate-100', 'text-slate-500');
    if (filter === 'all')   activeTab.classList.add('bg-slate-900', 'text-white');
    if (filter === 'benar') activeTab.classList.add('bg-emerald-500', 'text-white');
    if (filter === 'salah') activeTab.classList.add('bg-red-400', 'text-white');

    // Filter items
    document.querySelectorAll('.soal-item').forEach(el => {
        const status = el.dataset.status;
        if (filter === 'all' || status === filter) {
            el.style.display = '';
        } else {
            el.style.display = 'none';
        }
    });
}
</script>
    <script>
    // ── Score counter ──────────────────────────────────────────────
    (function () {
        const el = document.getElementById('scoreCounter');
        const ringPct = document.getElementById('ringPct');
        const target = parseInt(el.dataset.target, 10);
        const dur = 1400;
        const start = performance.now();
        function ease(t) { return 1 - Math.pow(1 - t, 3); }
        function tick(now) {
            const t = Math.min((now - start) / dur, 1);
            const val = Math.round(ease(t) * target);
            el.textContent = val;
            if (ringPct) ringPct.textContent = val + '%';
            if (t < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    })();

    // ── Progress ring ──────────────────────────────────────────────
    setTimeout(() => {
        const ring = document.getElementById('progressRing');
        if (ring) ring.style.strokeDashoffset = ring.dataset.offset;
    }, 300);

    // ── Accuracy bar ───────────────────────────────────────────────
    setTimeout(() => {
        const bar = document.getElementById('accuracyBar');
        if (bar) bar.style.width = bar.dataset.width + '%';
    }, 600);

    // ── Floating stars ─────────────────────────────────────────────
    (function () {
        const c = document.getElementById('starsContainer');
        if (!c) return;
        for (let i = 0; i < 30; i++) {
            const s = document.createElement('div');
            const size = Math.random() * 2 + 1;
            s.style.cssText = `
                position:absolute; border-radius:50%; background:rgba(255,255,255,${Math.random()*0.4+0.1});
                width:${size}px; height:${size}px;
                top:${Math.random()*100}%; left:${Math.random()*100}%;
                animation: twinkle ${2+Math.random()*3}s ease-in-out infinite;
                animation-delay:${Math.random()*3}s;
            `;
            c.appendChild(s);
        }
    })();

    // ── Chart.js ───────────────────────────────────────────────────
    (function () {
        const ctx = document.getElementById('historyChart');
        if (!ctx) return;

        const rawScores = @json($historyScores);
        const rawDates  = @json($historyDates);

        const labels = [];
        const data   = [];
        rawScores.forEach((s, i) => {
            if (s !== null) {
                labels.push(rawDates[i] ?? '');
                data.push(s);
            }
        });

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    data,
                    fill: true,
                    tension: 0.45,
                    pointRadius: 6,
                    pointHoverRadius: 9,
                    pointBackgroundColor: '#2962ff',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                    borderColor: '#2962ff',
                    borderWidth: 3,
                    backgroundColor: function(ctx) {
                        const chart = ctx.chart;
                        const {ctx: c, chartArea} = chart;
                        if (!chartArea) return 'transparent';
                        const g = c.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                        g.addColorStop(0, 'rgba(41,98,255,0.18)');
                        g.addColorStop(1, 'rgba(41,98,255,0.01)');
                        return g;
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#94a3b8',
                        bodyColor: '#fff',
                        bodyFont: { weight: 'bold', size: 16 },
                        padding: 12,
                        cornerRadius: 14,
                        callbacks: {
                            label: ctx => ' ' + ctx.parsed.y + ' poin'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 12, weight: '600' } }
                    },
                    y: {
                        min: 0, max: 100,
                        grid: { color: 'rgba(148,163,184,0.1)', drawBorder: false },
                        border: { display: false },
                        ticks: {
                            color: '#94a3b8', font: { size: 12 },
                            stepSize: 25,
                            callback: v => v
                        }
                    }
                }
            }
        });
    })();

    // ── Confetti ───────────────────────────────────────────────────
    @if($hasil->skor >= 80)
    (function () {
        const canvas = document.getElementById('confettiCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        canvas.width  = window.innerWidth;
        canvas.height = window.innerHeight;

        const pieces = Array.from({length: 120}, () => ({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height - canvas.height,
            r: Math.random() * 7 + 3,
            d: Math.random() * 4 + 2,
            color: ['#2962ff','#a78bfa','#22c55e','#f59e0b','#f472b6','#38bdf8'][Math.floor(Math.random()*6)],
            tilt: Math.random() * 10 - 10,
            tiltAngle: 0,
            tiltSpeed: Math.random() * 0.1 + 0.05
        }));

        let frame = 0;
        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            pieces.forEach(p => {
                p.tiltAngle += p.tiltSpeed;
                p.y += p.d;
                p.tilt = Math.sin(p.tiltAngle) * 12;
                if (p.y > canvas.height) { p.y = -10; p.x = Math.random() * canvas.width; }
                ctx.beginPath();
                ctx.lineWidth = p.r;
                ctx.strokeStyle = p.color;
                ctx.globalAlpha = 0.85;
                ctx.moveTo(p.x + p.tilt + p.r / 2, p.y);
                ctx.lineTo(p.x + p.tilt, p.y + p.tilt + p.r / 2);
                ctx.stroke();
            });
            frame++;
            if (frame < 300) requestAnimationFrame(draw);
            else ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
        draw();
    })();
    @endif

    </script>

    <style>
        @keyframes fadeup {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes twinkle {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50%       { opacity: 0.9; transform: scale(1.5); }
        }
        .animate-fadeup { animation: fadeup 0.5s ease both; }
        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(15,23,42,0.08); }
    </style>

</x-app-layout>
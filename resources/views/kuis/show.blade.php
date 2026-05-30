<x-app-layout>

    @php
        $soals = $kuis->soalKuis;
        $total = $soals->count();
        $nomor = request()->input('soal', 1);
        if ($nomor < 1) $nomor = 1;
        if ($nomor > $total) $nomor = $total;
        $soal = $soals[$nomor - 1];
        $progress = $total > 0 ? round(($nomor / $total) * 100) : 0;
    @endphp

    <div class="min-h-screen bg-[#f5f7fb] py-6 px-4">
        <div class="max-w-3xl mx-auto">

            <!-- TOP -->
            <div class="mb-5 animate-fadein">
                <a href="{{ route('materi.show', $kuis->materi_id) }}"
                   class="inline-flex items-center gap-2 text-[14px] text-gray-500 hover:text-blue-600 transition-all duration-200">
                    ← Kembali ke Materi
                </a>
                <h1 class="text-[30px] font-bold text-gray-800 mt-3 tracking-tight">Kuis</h1>
                <div class="flex items-center gap-3 mt-3">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-red-50 to-blue-500 flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2H9a2 2 0 01-2-2V7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[15px] font-semibold text-gray-700">{{ $kuis->materi->judul ?? '' }}.pdf</p>
                        <p class="text-[13px] text-gray-400">File Materi Pembelajaran</p>
                    </div>
                </div>
            </div>

            <!-- TIMER -->
            <div id="timerBox" class="fixed top-20 right-5 z-[9999] bg-white border border-gray-200 rounded-2xl px-5 py-4 shadow-[0_8px_25px_rgba(0,0,0,0.08)] text-center min-w-[110px] transition-all duration-300">
                <div class="flex items-center justify-center gap-2 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" id="timerIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-[13px] font-medium">Waktu</span>
                </div>
                <div id="timer" class="text-[28px] font-bold text-gray-800 mt-1 tracking-tight transition-colors duration-300">10:00</div>
            </div>

            <!-- NAVIGATOR SOAL -->
            <div class="bg-white border border-gray-100 rounded-[20px] shadow-[0_4px_20px_rgb(0,0,0,0.04)] px-5 py-4 mb-4 animate-fadein">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[12px] font-semibold text-gray-400 uppercase tracking-wider">Navigasi Soal</p>
                    <p class="text-[12px] text-gray-400"><span id="answeredCount">0</span>/{{ $total }} dijawab</p>
                </div>
                <div class="flex flex-wrap gap-2" id="soalNav">
                    @foreach($soals as $i => $s)
                        @php $n = $i + 1; @endphp
                        <button type="button"
                            onclick="navigasi({{ $n }})"
                            data-nav="{{ $n }}"
                            data-soalid="{{ $s->id }}"
                            class="nav-btn relative w-9 h-9 rounded-xl text-[13px] font-bold transition-all duration-200
                                {{ $n === $nomor
                                    ? 'bg-gradient-to-br from-blue-600 to-cyan-500 text-white shadow-md ring-2 ring-blue-300 ring-offset-1 scale-110'
                                    : 'bg-gray-100 text-gray-500 hover:bg-blue-50 hover:text-blue-600 hover:scale-105' }}">
                            {{ $n }}
                            <span class="nav-dot absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-green-400 border-2 border-white rounded-full hidden"></span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- CARD -->
            <div class="bg-white border border-gray-100 rounded-[32px] shadow-[0_10px_40px_rgb(0,0,0,0.05)] overflow-hidden animate-slidein">

                <!-- PROGRESS -->
                <div class="px-7 pt-7 pb-5 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-[16px] font-semibold text-gray-800">Soal {{ $nomor }} dari {{ $total }}</h2>
                        <div class="text-[14px] font-bold text-blue-600">{{ $progress }}%</div>
                    </div>
                    <div class="w-full h-[8px] rounded-full bg-gray-100 overflow-hidden">
                        <div id="progressBar" class="h-full rounded-full bg-gradient-to-r from-blue-600 to-cyan-500 transition-all duration-700 ease-out" style="width: 0%"></div>
                    </div>
                </div>

                <!-- FORM -->
                <form id="kuisForm"
                      method="POST"
                      action="{{ route('kuis.submit', $kuis->id) }}"
                      class="px-7 py-7">
                    @csrf
                    <div id="hiddenJawaban"></div>

                    <!-- PERTANYAAN -->
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-100 rounded-[28px] p-6 question-animate">
                        <div class="inline-flex items-center gap-2 bg-white border border-blue-100 rounded-full px-4 py-2 text-[13px] font-semibold text-blue-700 shadow-sm">
                            <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[11px] flex items-center justify-center font-bold">{{ $nomor }}</span>
                            Pertanyaan {{ $nomor }}
                        </div>
                        <h2 class="text-[22px] leading-[1.6] font-bold text-gray-800 mt-5">{{ $soal->pertanyaan }}</h2>
                    </div>

                    <!-- OPSI -->
                    @php
                        $opsi = [
                            'a' => $soal->opsi_a,
                            'b' => $soal->opsi_b,
                            'c' => $soal->opsi_c,
                            'd' => $soal->opsi_d,
                        ];
                    @endphp

                    <div class="space-y-3 mt-7" id="opsiContainer">
                        @foreach($opsi as $key => $value)
                            <label class="opsi-label group relative flex items-center gap-4 border-2 border-gray-200 rounded-[20px] px-5 py-4 cursor-pointer transition-all duration-200 hover:border-blue-300 hover:bg-blue-50/30 hover:-translate-y-0.5 hover:shadow-md active:scale-[0.99]">
                                <input
                                    type="radio"
                                    name="jawaban_soal_ini"
                                    value="{{ $key }}"
                                    data-soal-id="{{ $soal->id }}"
                                    class="sr-only opsi-radio"
                                >
                                <div class="opsi-badge w-11 h-11 rounded-2xl bg-gray-100 flex items-center justify-center font-bold text-[15px] text-gray-600 transition-all duration-200 shrink-0">
                                    {{ strtoupper($key) }}
                                </div>
                                <p class="opsi-text flex-1 text-[15px] leading-relaxed text-gray-700 transition-all duration-200">
                                    {{ $value }}
                                </p>
                                <div class="opsi-check w-6 h-6 rounded-full bg-blue-600 items-center justify-center hidden shrink-0 scale-0 transition-transform duration-200">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- BUTTON -->
                    <div class="flex items-center justify-between mt-10">
                        @if($nomor > 1)
                            <button type="button" onclick="navigasi({{ $nomor - 1 }})"
                                    class="bubble-btn bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-200">
                                <svg class="w-4 h-4 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                </svg>
                                <span class="relative z-10">Sebelumnya</span>
                                <span class="bubble-effect"></span>
                            </button>
                        @else
                            <div></div>
                        @endif

                        @if($nomor < $total)
                            <button type="button" onclick="navigasi({{ $nomor + 1 }})"
                                    class="bubble-btn bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg">
                                <span class="relative z-10">Selanjutnya</span>
                                <svg class="w-4 h-4 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span class="bubble-effect"></span>
                            </button>
                        @else
                            <button type="button" onclick="confirmSubmit()"
                                    class="bubble-btn bg-gradient-to-r from-green-500 to-emerald-400 text-white shadow-lg">
                                <svg class="w-4 h-4 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="relative z-10">Selesai & Kumpulkan</span>
                                <span class="bubble-effect"></span>
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            <p class="text-center text-[12px] text-gray-400 mt-5 animate-fadein">
                💡 Jawaban tersimpan otomatis. Kamu bisa navigasi soal kapan saja.
            </p>
        </div>
    </div>

    <!-- MODAL KONFIRMASI SUBMIT -->
    <div id="submitModal" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/40 px-4 hidden">
        <div id="submitModalBox" class="bg-white rounded-[28px] shadow-2xl w-full max-w-sm p-7 scale-90 opacity-0 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-[18px] font-bold text-gray-900 text-center mb-2">Kumpulkan Jawaban?</h3>
            <p class="text-[13px] text-gray-500 text-center mb-2">Pastikan semua soal sudah dijawab sebelum mengumpulkan.</p>
            <p class="text-[13px] font-semibold text-center mb-6" id="modalAnsweredInfo"></p>
            <div class="flex gap-3">
                <button onclick="closeModal()" class="flex-1 px-4 py-3 text-sm text-gray-600 font-semibold rounded-2xl border-2 border-gray-200 hover:bg-gray-50 transition-colors">
                    Cek Lagi
                </button>
                <button onclick="submitKuis()" class="flex-1 px-4 py-3 text-sm text-white font-semibold rounded-2xl bg-gradient-to-r from-green-500 to-emerald-400 hover:opacity-90 shadow-md transition-opacity">
                    Ya, Kumpulkan!
                </button>
            </div>
        </div>
    </div>

    <script>
        // ✅ Semua konstanta di atas agar tersedia di seluruh fungsi
        const KUIS_ID     = '{{ $kuis->id }}';
        const SOAL_ID     = '{{ $soal->id }}';
        const TOTAL_SOAL  = {{ $total }};
        const NOMOR_AKTIF = {{ $nomor }};
        const STORAGE_KEY = 'kuis_' + KUIS_ID;
        const TIMER_KEY   = 'kuis_timer_' + KUIS_ID;
        const DURATION    = 600;

        const navBtns   = document.querySelectorAll('.nav-btn');
        const soalIdMap = {};
        navBtns.forEach(btn => {
            soalIdMap[parseInt(btn.dataset.nav)] = btn.dataset.soalid;
        });

        function getJawaban() {
            return JSON.parse(sessionStorage.getItem(STORAGE_KEY) || '{}');
        }

        function saveJawaban(soalId, jawaban) {
            const data = getJawaban();
            data[soalId] = jawaban;
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify(data));
            refreshNavUI();
        }

        function refreshNavUI() {
            const data = getJawaban();
            let answered = 0;
            navBtns.forEach(btn => {
                const n        = parseInt(btn.dataset.nav);
                const sId      = btn.dataset.soalid;
                const dot      = btn.querySelector('.nav-dot');
                const isActive = n === NOMOR_AKTIF;

                if (data[sId] !== undefined) {
                    answered++;
                    dot.classList.remove('hidden');
                    if (!isActive) {
                        btn.classList.remove('bg-gray-100', 'text-gray-500');
                        btn.classList.add('bg-green-50', 'text-green-600', 'border', 'border-green-200');
                    }
                } else {
                    dot.classList.add('hidden');
                    if (!isActive) {
                        btn.classList.remove('bg-green-50', 'text-green-600', 'border', 'border-green-200');
                        btn.classList.add('bg-gray-100', 'text-gray-500');
                    }
                }

                if (isActive) {
                    btn.classList.remove('bg-gray-100','text-gray-500','bg-green-50','text-green-600','border','border-green-200');
                    btn.classList.add('bg-gradient-to-br','from-blue-600','to-cyan-500','text-white','shadow-md','ring-2','ring-blue-300','ring-offset-1','scale-110');
                }
            });
            document.getElementById('answeredCount').textContent = answered;
        }

        function applyOpsiStyle() {
            document.querySelectorAll('.opsi-label').forEach(label => {
                const radio = label.querySelector('.opsi-radio');
                const badge = label.querySelector('.opsi-badge');
                const text  = label.querySelector('.opsi-text');
                const check = label.querySelector('.opsi-check');

                if (radio.checked) {
                    label.classList.add('border-blue-500', 'bg-blue-50', 'shadow-md', '-translate-y-0.5');
                    label.classList.remove('border-gray-200');
                    badge.classList.add('bg-blue-600', 'text-white', 'scale-110');
                    badge.classList.remove('bg-gray-100', 'text-gray-600');
                    text.classList.add('text-blue-700', 'font-semibold');
                    text.classList.remove('text-gray-700');
                    check.classList.remove('hidden');
                    check.classList.add('flex');
                    setTimeout(() => check.classList.remove('scale-0'), 10);
                } else {
                    label.classList.remove('border-blue-500', 'bg-blue-50', 'shadow-md', '-translate-y-0.5');
                    label.classList.add('border-gray-200');
                    badge.classList.remove('bg-blue-600', 'text-white', 'scale-110');
                    badge.classList.add('bg-gray-100', 'text-gray-600');
                    text.classList.remove('text-blue-700', 'font-semibold');
                    text.classList.add('text-gray-700');
                    check.classList.add('hidden', 'scale-0');
                    check.classList.remove('flex');
                }
            });
        }

        function restoreJawaban() {
            const data    = getJawaban();
            const jawaban = data[SOAL_ID];
            if (jawaban) {
                const radio = document.querySelector(`.opsi-radio[value="${jawaban}"]`);
                if (radio) radio.checked = true;
            }
            applyOpsiStyle();
            refreshNavUI();
        }

        document.querySelectorAll('.opsi-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                saveJawaban(SOAL_ID, this.value);
                applyOpsiStyle();
            });
        });

        function navigasi(nomorSoal) {
            const card = document.querySelector('.animate-slidein');
            if (card) {
                card.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
                card.style.opacity    = '0';
                card.style.transform  = 'translateY(8px)';
            }
            setTimeout(() => {
                window.location.href = '{{ route('kuis.show', $kuis->id) }}?soal=' + nomorSoal;
            }, 150);
        }

        function confirmSubmit() {
            const data     = getJawaban();
            const answered = Object.keys(data).length;
            const info     = document.getElementById('modalAnsweredInfo');
            if (answered < TOTAL_SOAL) {
                info.innerHTML = `<span class="text-orange-500">⚠️ ${answered} dari ${TOTAL_SOAL} soal sudah dijawab</span>`;
            } else {
                info.innerHTML = `<span class="text-green-600">✅ Semua ${TOTAL_SOAL} soal sudah dijawab!</span>`;
            }
            const modal    = document.getElementById('submitModal');
            const modalBox = document.getElementById('submitModalBox');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalBox.classList.remove('scale-90', 'opacity-0');
                modalBox.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            const modal    = document.getElementById('submitModal');
            const modalBox = document.getElementById('submitModalBox');
            modalBox.classList.add('scale-90', 'opacity-0');
            modalBox.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => modal.classList.add('hidden'), 250);
        }

        function submitKuis() {
            const data      = getJawaban();
            const form      = document.getElementById('kuisForm');
            const hiddenDiv = document.getElementById('hiddenJawaban');
            hiddenDiv.innerHTML = '';

            for (const [soalId, jawaban] of Object.entries(data)) {
                const input = document.createElement('input');
                input.type  = 'hidden';
                input.name  = `jawaban[${soalId}]`;
                input.value = jawaban;
                hiddenDiv.appendChild(input);
            }

            // Kirim waktu pengerjaan
            const waktuInput  = document.createElement('input');
            waktuInput.type   = 'hidden';
            waktuInput.name   = 'waktu_pengerjaan';
            waktuInput.value  = elapsedSeconds;
            hiddenDiv.appendChild(waktuInput);

            // Bersihkan sessionStorage
            sessionStorage.removeItem(TIMER_KEY);
            sessionStorage.removeItem(STORAGE_KEY);
            form.submit();
        }

        restoreJawaban();

        setTimeout(() => {
            document.getElementById('progressBar').style.width = '{{ $progress }}%';
        }, 300);

        // ✅ TIMER — pakai sessionStorage agar tidak reset saat pindah soal
        const timerEl   = document.getElementById('timer');
        const timerBox  = document.getElementById('timerBox');
        const timerIcon = document.getElementById('timerIcon');

        let startTime = sessionStorage.getItem(TIMER_KEY);
        if (!startTime) {
            startTime = Date.now();
            sessionStorage.setItem(TIMER_KEY, String(startTime));
        } else {
            startTime = parseInt(startTime);
        }

        // ✅ FIX: hitung elapsedSeconds langsung, bukan tunggu interval pertama
        let elapsedSeconds = Math.floor((Date.now() - startTime) / 1000);

        // ✅ FIX: render timer LANGSUNG saat load, supaya tidak flash "10:00"
        function renderTimer() {
            elapsedSeconds  = Math.floor((Date.now() - startTime) / 1000);
            const remaining = Math.max(DURATION - elapsedSeconds, 0);
            const minutes   = Math.floor(remaining / 60);
            const seconds   = remaining % 60;

            timerEl.textContent = `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;

            if (remaining <= 120) {
                timerEl.classList.add('text-red-600');
                timerEl.classList.remove('text-gray-800');
                timerBox.classList.add('border-red-200', 'bg-red-50');
                timerBox.classList.remove('border-gray-200');
                timerIcon.classList.add('text-red-500');
                timerIcon.classList.remove('text-blue-500');
            }
            if (remaining <= 30) {
                timerEl.style.opacity = timerEl.style.opacity === '0.4' ? '1' : '0.4';
            }
            if (remaining <= 0) {
                clearInterval(timerInterval);
                submitKuis();
            }
        }

        // ✅ FIX: render sekali langsung, BARU jalankan interval
        renderTimer();
        const timerInterval = setInterval(renderTimer, 1000);
    </script>

    <style>
        @keyframes fadein {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes slidein {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes opsi-pop {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fadein  { animation: fadein  0.4s ease both; }
        .animate-slidein { animation: slidein 0.5s ease both; }

        .opsi-label {
            animation: opsi-pop 0.35s ease both;
            user-select: none;
        }
        .opsi-label:nth-child(1) { animation-delay: 0ms; }
        .opsi-label:nth-child(2) { animation-delay: 60ms; }
        .opsi-label:nth-child(3) { animation-delay: 120ms; }
        .opsi-label:nth-child(4) { animation-delay: 180ms; }

        .bubble-btn {
            position: relative; overflow: hidden;
            display: inline-flex; align-items: center;
            justify-content: center; gap: 8px;
            padding: 12px 22px; border-radius: 16px;
            font-size: 14px; font-weight: 700;
            transition: all .2s ease;
        }
        .bubble-btn:hover  { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
        .bubble-btn:active { transform: scale(.94); }
        .bubble-effect {
            position: absolute; width: 12px; height: 12px;
            background: rgba(255,255,255,.45); border-radius: 999px;
            top: 50%; left: 50%;
            transform: translate(-50%,-50%) scale(0);
            transition: transform .55s ease, opacity .55s ease; opacity: 0;
        }
        .bubble-btn:active .bubble-effect {
            transform: translate(-50%,-50%) scale(18); opacity: 1;
        }
        .question-animate { animation: fadein 0.4s ease both; }
    </style>

</x-app-layout>
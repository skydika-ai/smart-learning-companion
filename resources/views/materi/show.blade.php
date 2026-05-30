{{-- resources/views/materi/show.blade.php --}}
<x-app-layout>

    <div class="max-w-6xl mx-auto pb-6">

        {{-- HEADER --}}
        <div class="mb-6">
            <a href="{{ route('materi.index') }}"
               class="inline-flex items-center gap-1.5 text-[13px] text-gray-400 hover:text-blue-600 transition-colors mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Materi
            </a>

            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-[24px] font-bold text-gray-900 tracking-tight leading-tight">
                        {{ $materi->judul }}
                    </h1>
                    <p class="text-sm text-gray-400 mt-1">
                        Diupload {{ $materi->created_at->format('d M Y') }}
                    </p>
                </div>
                <a href="{{ asset('storage/' . $materi->file_path) }}"
                   target="_blank"
                   class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl
                          bg-gradient-to-r from-blue-600 to-indigo-600
                          text-white text-sm font-semibold
                          shadow-md shadow-blue-200/50
                          hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 7v10a2 2 0 002 2h6a2 2 0 002-2V9l-4-4H9a2 2 0 00-2 2z"/>
                    </svg>
                    Buka PDF
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-5 items-start">

            {{-- RINGKASAN --}}
            <div class="xl:col-span-7">
                <div class="bg-white border border-gray-100 rounded-[24px] shadow-sm overflow-hidden">

                    {{-- Card header --}}
                    <div class="px-6 pt-5 pb-4 border-b border-gray-100">
                        <div class="flex items-center justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600
                                            flex items-center justify-center shadow-md shadow-blue-200/50">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-[16px] font-bold text-gray-900">Ringkasan AI</h2>
                                    <p class="text-[12px] text-gray-400 mt-0.5">Poin-poin penting dari materi</p>
                                </div>
                            </div>

                            @if($materi->ringkasan)
                                <div class="flex items-center gap-2 flex-wrap">
                                    <button id="copyButton" onclick="copyRingkasan()"
                                            class="bubble-btn bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-100">
                                        <span class="relative z-10 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 10h6a2 2 0 002-2v-8a2 2 0 00-2-2h-6a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                            <span id="copyText">Copy</span>
                                        </span>
                                    </button>
                                    <button onclick="downloadRingkasan()"
                                            class="bubble-btn bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-100">
                                        <span class="relative z-10 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Card content --}}
                    <div class="px-6 py-5">
                        @if($materi->ringkasan)
                            <div id="ringkasanText" class="space-y-3">
                                @php
                                    $raw = $materi->ringkasan ?? '';

                                    // Coba split by "Poin N:"
                                    $poinList = preg_split('/Poin\s*\d+\s*:\s*/i', $raw);
                                    $poinList = array_values(array_filter(array_map('trim', $poinList)));

                                    // Fallback: jika hanya 1 blok (tidak ada format Poin N:), split by paragraf/kalimat
                                    if (count($poinList) <= 1 && !empty($raw)) {
                                        // Coba split per paragraf dulu
                                        $poinList = preg_split('/\n{2,}/', trim($raw));
                                        $poinList = array_values(array_filter(array_map('trim', $poinList)));

                                        // Jika masih 1 blok panjang, split per kalimat
                                        if (count($poinList) <= 1) {
                                            $poinList = preg_split('/(?<=[.!?])\s+/', trim($raw));
                                            $poinList = array_values(array_filter(array_map('trim', $poinList), fn($k) => strlen($k) > 20));
                                        }
                                    }

                                    $sisanya = count($poinList) - 4;
                                    $noPoin = 0; 
                                @endphp
                                @foreach($poinList as $paragraph)
                                    @if(trim($paragraph))
                                        @php $noPoin++; @endphp
                                        <div class="flex items-start gap-3 p-3 rounded-2xl
                                                    bg-gray-50/60 hover:bg-blue-50/40 transition-colors duration-200">
                                            <div class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600
                                                        text-[11px] font-bold flex items-center justify-center
                                                        mt-0.5 shrink-0">
                                                {{ $noPoin }}
                                            </div>
                                            <p class="text-[14px] leading-relaxed text-gray-700">
                                                {{ trim(str_replace('•', '', $paragraph)) }}
                                            </p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div x-data
                                 x-init="setInterval(async () => {
                                     const r = await fetch(window.location.href);
                                     const h = await r.text();
                                     if(h.includes('Ringkasan AI')) window.location.reload();
                                 }, 3000)"
                                 class="py-14 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600
                                            mx-auto flex items-center justify-center shadow-md shadow-blue-200/50 animate-pulse">
                                    <svg class="w-5 h-5 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-[15px] font-semibold text-gray-700">AI sedang membuat ringkasan</h3>
                                <p class="text-[13px] text-gray-400 mt-1">Mohon tunggu beberapa saat...</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- KUIS --}}
            <div class="xl:col-span-5">
                <div class="bg-white border border-gray-100 rounded-[24px] shadow-sm overflow-hidden">

                    <div class="px-5 pt-5 pb-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600
                                        flex items-center justify-center shadow-md shadow-violet-200/50">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-[16px] font-bold text-gray-900">Kuis Materi</h2>
                                <p class="text-[12px] text-gray-400 mt-0.5">Latihan pemahaman dari AI</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-5 py-5">
                        @if($materi->kuis->isEmpty())
                            <div class="py-14 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600
                                            mx-auto flex items-center justify-center shadow-md shadow-violet-200/50 animate-pulse">
                                    <svg class="w-5 h-5 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-[15px] font-semibold text-gray-700">AI sedang membuat kuis</h3>
                                <p class="text-[13px] text-gray-400 mt-1">Mohon tunggu beberapa saat...</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($materi->kuis as $kuis)
                                    @php
                                        $hasil = $kuis->hasilKuis->where('user_id', auth()->id())->first();
                                        $sudah = !is_null($hasil);
                                        $skor  = $sudah ? $hasil->skor : null;
                                        $skorColor = $skor >= 80 ? 'text-green-600' : ($skor >= 60 ? 'text-amber-500' : 'text-red-500');
                                        $skorBg    = $skor >= 80 ? 'bg-green-50'   : ($skor >= 60 ? 'bg-amber-50'    : 'bg-red-50');
                                    @endphp

                                    <div class="rounded-2xl overflow-hidden border transition-all duration-200
                                                {{ $sudah ? 'border-green-100 hover:border-green-200 hover:shadow-md hover:shadow-green-50' : 'border-gray-100 hover:border-violet-200 hover:shadow-md hover:shadow-violet-50' }}">

                                        {{-- Accent bar --}}
                                        <div class="h-1 w-full {{ $sudah ? 'bg-gradient-to-r from-green-400 to-emerald-500' : 'bg-gradient-to-r from-violet-500 to-purple-600' }}"></div>

                                        <div class="p-4">
                                            <div class="flex items-center justify-between gap-3">
                                                <div>
                                                    <h3 class="text-[14px] font-bold text-gray-900 leading-tight">
                                                        {{ $kuis->judul_kuis }}
                                                    </h3>
                                                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                                        <span class="text-[11px] font-semibold bg-violet-50 text-violet-600 px-2 py-0.5 rounded-full">
                                                            {{ $kuis->soalKuis->count() }} soal
                                                        </span>
                                                        @if($sudah)
                                                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $skorBg }} {{ $skorColor }}">
                                                                Skor {{ $skor }}
                                                            </span>
                                                        @else
                                                            <span class="text-[11px] text-gray-400">Belum dikerjakan</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2 shrink-0">
                                                    @if($sudah)
                                                        <a href="{{ route('kuis.result', [$kuis, $hasil]) }}"
                                                        class="flex items-center gap-1 px-3 py-2 rounded-xl
                                                                bg-gray-50 hover:bg-gray-100 text-gray-500
                                                                text-[12px] font-semibold transition-all duration-200">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                            </svg>
                                                            Hasil
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('kuis.show', $kuis) }}"
                                                    class="flex items-center gap-1.5 px-4 py-2 rounded-xl
                                                            text-white text-[13px] font-semibold
                                                            shadow-sm transition-all duration-200 hover:-translate-y-0.5
                                                            {{ $sudah
                                                                ? 'bg-gradient-to-r from-amber-400 to-orange-500 shadow-amber-200'
                                                                : 'bg-gradient-to-r from-violet-600 to-purple-600 shadow-violet-200' }}">
                                                        {{ $sudah ? 'Ulangi' : 'Mulai' }}
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>

    </div>

    <script>
        function copyRingkasan() {
            const text = document.getElementById('ringkasanText').innerText;
            navigator.clipboard.writeText(text);
            const ct = document.getElementById('copyText');
            ct.innerText = 'Tersalin ✓';
            document.getElementById('copyButton').style.boxShadow = '0 0 0 4px rgba(34,197,94,.15)';
            setTimeout(() => {
                ct.innerText = 'Copy';
                document.getElementById('copyButton').style.boxShadow = '';
            }, 1800);
        }
        function downloadRingkasan() {
            const text = document.getElementById('ringkasanText').innerText;
            const a = document.createElement('a');
            a.href = URL.createObjectURL(new Blob([text], { type: 'text/plain' }));
            a.download = 'ringkasan-{{ Str::slug($materi->judul) }}.txt';
            a.click();
        }
    </script>

    <style>
        .bubble-btn {
            position: relative; overflow: hidden;
            display: inline-flex; align-items: center; justify-content: center;
            padding: 8px 14px; border-radius: 12px;
            font-size: 12px; font-weight: 600;
            transition: all .18s ease;
        }
        .bubble-btn:hover { transform: translateY(-1px); }
        .bubble-btn:active { transform: scale(.93); }
    </style>

</x-app-layout>
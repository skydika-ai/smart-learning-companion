<x-app-layout>

    <div class="max-w-5xl mx-auto px-4 pb-10">

        <!-- HEADER -->
        <div class="mb-6">
            <h1 class="text-[26px] font-bold text-gray-800 tracking-tight">Ringkasan AI</h1>
            <p class="text-[13px] text-gray-400 mt-1">Semua ringkasan materi yang telah dibuat oleh AI</p>
        </div>

        @if($materis->isEmpty())

            <!-- EMPTY STATE -->
            <div class="bg-white border border-gray-100 rounded-[26px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] py-20 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 mx-auto flex items-center justify-center shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="mt-5 text-[16px] font-semibold text-gray-700">Belum ada ringkasan</h3>
                <p class="text-[13px] text-gray-400 mt-1">Upload materi terlebih dahulu agar AI bisa membuat ringkasan</p>
                <a href="{{ route('materi.index') }}"
                   class="mt-5 inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-[13px] font-semibold px-5 py-2.5 rounded-xl shadow-md hover:opacity-90 transition">
                    Upload Materi
                </a>
            </div>

        @else

            <div class="space-y-4">

                @foreach($materis as $materi)

                    <div class="bg-white border border-gray-100 rounded-[26px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">

                        <!-- CARD HEADER -->
                        <div class="px-6 pt-5 pb-4 border-b border-gray-100 flex items-center justify-between gap-4 flex-wrap">

                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-md flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-[15px] font-semibold text-gray-800">{{ $materi->judul }}</h2>
                                    <p class="text-[12px] text-gray-400 mt-[2px]">{{ $materi->created_at->format('d M Y') }}</p>
                                </div>
                            </div>

                            <a href="{{ route('materi.show', $materi) }}"
                               class="text-[12px] font-semibold text-blue-600 hover:text-blue-700 transition">
                                Lihat Materi →
                            </a>

                        </div>

                        <!-- CARD BODY -->
                        <div class="px-6 py-5">

                            @if($materi->ringkasan)

                                @php
                                    $raw = $materi->ringkasan ?? '';

                                    $poinList = preg_split('/Poin\s*\d+\s*:\s*/i', $raw);
                                    $poinList = array_values(array_filter(array_map('trim', $poinList)));

                                    if (count($poinList) <= 1 && !empty($raw)) {
                                        $poinList = preg_split('/\n{2,}/', trim($raw));
                                        $poinList = array_values(array_filter(array_map('trim', $poinList)));

                                        if (count($poinList) <= 1) {
                                            $poinList = preg_split('/(?<=[.!?])\s+/', trim($raw));
                                            $poinList = array_values(array_filter(array_map('trim', $poinList), fn($k) => strlen($k) > 20));
                                        }
                                    }

                                    $sisanya = count($poinList) - 4;
                                @endphp

                                <div x-data="{ expanded: false }">

                                    <div class="space-y-3">
                                        @foreach($poinList as $index => $poin)
                                            <div class="flex items-start gap-3"
                                                @if($index >= 4) x-show="expanded" x-transition @endif>
                                                {{-- Ganti bullet dot → nomor badge --}}
                                                <span class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 text-[11px] font-bold
                                                            flex items-center justify-center flex-shrink-0 mt-0.5">
                                                    {{ $index + 1 }}
                                                </span>
                                                <p class="text-[14px] leading-[1.8] text-gray-700">{{ $poin }}</p>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($sisanya > 0)
                                        <button @click="expanded = !expanded"
                                                class="mt-3 text-[12px] text-blue-500 hover:underline focus:outline-none">
                                            <span x-show="!expanded">+{{ $sisanya }} poin lainnya — tampilkan semua</span>
                                            <span x-show="expanded">Sembunyikan</span>
                                        </button>
                                    @endif

                                </div>

                            @else
                                {{-- MASIH PROSES --}}
                                <div class="flex items-center gap-3 py-3">
                                    <svg class="w-5 h-5 text-blue-400 animate-spin flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    <p class="text-[13px] text-gray-400">AI sedang memproses ringkasan...</p>
                                </div>
                            @endif

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>

</x-app-layout>
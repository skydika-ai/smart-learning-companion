<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 pb-10">

        <!-- HEADER -->
        <div class="mb-6">
            <h1 class="text-[26px] font-bold text-gray-800 tracking-tight">Kuis Saya</h1>
            <p class="text-[13px] text-gray-400 mt-1">Semua kuis yang tersedia dari materi kamu</p>
        </div>

        @if($kuisList->isEmpty())
            <div class="bg-white border border-gray-100 rounded-[26px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] py-20 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 mx-auto flex items-center justify-center shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="mt-5 text-[16px] font-semibold text-gray-700">Belum ada kuis</h3>
                <p class="text-[13px] text-gray-400 mt-1">Upload materi terlebih dahulu agar AI bisa membuat kuis</p>
                <a href="{{ route('materi.index') }}"
                   class="mt-5 inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-[13px] font-semibold px-5 py-2.5 rounded-xl shadow-md hover:opacity-90 transition">
                    Upload Materi
                </a>
            </div>

        @else
            <div class="space-y-4">
                @foreach($kuisList as $kuis)
                    @php
                        $hasil = $kuis->hasilKuis->first();
                        $sudahDikerjakan = !is_null($hasil);
                    @endphp

                    <div class="bg-white border border-gray-100 rounded-[26px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 flex items-center justify-between gap-4 flex-wrap">

                        <div class="flex items-center gap-4">
                            <!-- ICON -->
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-md flex-shrink-0
                                {{ $sudahDikerjakan ? 'bg-gradient-to-br from-green-400 to-emerald-500' : 'bg-gradient-to-br from-blue-500 to-cyan-500' }}">
                                @if($sudahDikerjakan)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>

                            <div>
                                <h3 class="text-[15px] font-semibold text-gray-800">{{ $kuis->judul_kuis }}</h3>
                                <p class="text-[12px] text-gray-400 mt-[2px]">
                                    {{ $kuis->soalKuis->count() }} soal
                                    · dari <span class="text-blue-500">{{ $kuis->materi->judul }}</span>
                                </p>
                                @if($sudahDikerjakan)
                                    <span class="inline-block mt-1 text-[11px] font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                                        Skor: {{ $hasil->skor }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($sudahDikerjakan)
                                <a href="{{ route('kuis.result', [$kuis, $hasil]) }}"
                                   class="text-[12px] font-semibold text-gray-500 hover:text-gray-700 transition px-3 py-2 rounded-xl hover:bg-gray-50">
                                    Lihat Hasil
                                </a>
                            @endif
                            <a href="{{ route('kuis.show', $kuis) }}"
                               class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-[13px] font-semibold px-4 py-2 rounded-xl shadow-md hover:opacity-90 transition">
                                {{ $sudahDikerjakan ? 'Kerjakan Ulang' : 'Mulai Kuis' }}
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
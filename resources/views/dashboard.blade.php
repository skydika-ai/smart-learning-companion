<x-app-layout>

    {{-- HEADER --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500 mb-1">Dashboard</p>
                <h1 class="text-[28px] font-extrabold tracking-tight text-gray-900 leading-none">
                    Halo, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="text-sm text-gray-400 mt-2">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
        <div class="mt-5 h-px bg-gradient-to-r from-indigo-100 via-gray-200 to-transparent"></div>
    </div>

    {{-- STATS --}}
    @php
        $rataRata = $hasilKuis->count() > 0 ? round($hasilKuis->avg('skor')) : 0;
        $stats = [
            [
                'label' => 'Total Materi',
                'value' => $materis->count(),
                'sub'   => 'dokumen',
                'icon'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'color' => 'bg-blue-50 text-blue-600',
            ],
            [
                'label' => 'Diringkas AI',
                'value' => $materis->whereNotNull('ringkasan')->count(),
                'sub'   => 'selesai',
                'icon'  => 'M13 10V3L4 14h7v7l9-11h-7z',
                'color' => 'bg-indigo-50 text-indigo-600',
            ],
            [
                'label' => 'Kuis Dikerjakan',
                'value' => $hasilKuis->count(),
                'sub'   => 'kuis',
                'icon'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                'color' => 'bg-violet-50 text-violet-600',
            ],
            [
                'label' => 'Rata-rata Nilai',
                'value' => $rataRata,
                'sub'   => 'dari 100',
                'icon'  => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
                'color' => $rataRata >= 80 ? 'bg-emerald-50 text-emerald-600' : ($rataRata >= 60 ? 'bg-amber-50 text-amber-500' : 'bg-rose-50 text-rose-500'),
            ],
        ];
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach($stats as $stat)
            <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">{{ $stat['label'] }}</p>
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ $stat['color'] }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-gray-900">{{ $stat['value'] }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $stat['sub'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- PROGRESS BAR rata-rata --}}
    @if($hasilKuis->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-100 px-5 py-4 mb-6 flex items-center gap-4">
            <div class="shrink-0">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-1">Performa Keseluruhan</p>
                <p class="text-sm font-bold {{ $rataRata >= 80 ? 'text-emerald-600' : ($rataRata >= 60 ? 'text-amber-500' : 'text-rose-500') }}">
                    {{ $rataRata >= 80 ? 'Sangat Baik 🎉' : ($rataRata >= 60 ? 'Cukup Baik 💪' : 'Perlu Ditingkatkan 📚') }}
                </p>
            </div>
            <div class="flex-1">
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700
                                {{ $rataRata >= 80 ? 'bg-emerald-400' : ($rataRata >= 60 ? 'bg-amber-400' : 'bg-rose-400') }}"
                         style="width: {{ $rataRata }}%"></div>
                </div>
            </div>
            <span class="text-lg font-black text-gray-900 shrink-0">{{ $rataRata }}<span class="text-sm font-normal text-gray-400">/100</span></span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

        {{-- MATERI TERAKHIR --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-gray-100">
            <div class="px-5 pt-5 pb-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-[15px] font-bold text-gray-900">Materi Terakhir</h2>
                <a href="{{ route('materi.index') }}"
                   class="text-[12px] font-semibold text-indigo-600 hover:text-indigo-700 transition">
                    Lihat semua →
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($materis->take(5) as $materi)
                    <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50/60 transition-colors">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0
                                    {{ $materi->ringkasan ? 'bg-indigo-50' : 'bg-amber-50' }}">
                            <svg class="w-4 h-4 {{ $materi->ringkasan ? 'text-indigo-500' : 'text-amber-500' }}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[14px] font-semibold text-gray-800 truncate">{{ $materi->judul }}</p>
                            <p class="text-[12px] text-gray-400">{{ $materi->created_at->diffForHumans() }}</p>
                        </div>
                        @if($materi->ringkasan)
                            <span class="text-[11px] font-semibold bg-emerald-50 text-emerald-600 px-2.5 py-0.5 rounded-full shrink-0">
                                Selesai
                            </span>
                        @else
                            <span class="text-[11px] font-semibold bg-amber-50 text-amber-500 px-2.5 py-0.5 rounded-full shrink-0 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                Proses
                            </span>
                        @endif
                    </div>
                @empty
                    <div class="py-14 text-center text-gray-400">
                        <p class="text-sm">Belum ada materi diupload.</p>
                        <a href="{{ route('materi.create') }}"
                           class="mt-3 inline-flex text-[13px] font-semibold text-indigo-600 hover:underline">
                            Upload sekarang →
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- KANAN: Quick Actions + Kuis Terakhir --}}
        <div class="lg:col-span-2 flex flex-col gap-5">

            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <h2 class="text-[15px] font-bold text-gray-900 mb-4">Aksi Cepat</h2>
                <div class="space-y-2.5">
                    <a href="{{ route('materi.create') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl
                              bg-indigo-600 hover:bg-indigo-700
                              text-white font-semibold text-[13px]
                              shadow-md shadow-indigo-200/50
                              transition-all duration-200 hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Upload Materi Baru
                    </a>
                    <a href="{{ route('ringkasan.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl
                              bg-gray-50 hover:bg-gray-100
                              text-gray-700 font-semibold text-[13px]
                              border border-gray-100
                              transition-all duration-200">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Lihat Ringkasan AI
                    </a>
                    <a href="{{ route('kuis.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl
                              bg-gray-50 hover:bg-gray-100
                              text-gray-700 font-semibold text-[13px]
                              border border-gray-100
                              transition-all duration-200">
                        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Kerjakan Kuis
                    </a>
                </div>
            </div>

            {{-- Kuis Terakhir --}}
            <div class="bg-white rounded-2xl border border-gray-100 flex-1">
                <div class="px-5 pt-5 pb-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-[15px] font-bold text-gray-900">Kuis Terakhir</h2>
                    <a href="{{ route('kuis.index') }}"
                       class="text-[12px] font-semibold text-indigo-600 hover:text-indigo-700 transition">
                        Lihat semua →
                    </a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($hasilKuis->take(3) as $hasil)
                        @php
                            $s = $hasil->skor;
                            $c = $s >= 80 ? 'text-emerald-600 bg-emerald-50' : ($s >= 60 ? 'text-amber-500 bg-amber-50' : 'text-rose-500 bg-rose-50');
                        @endphp
                        <div class="flex items-center gap-3 px-5 py-3.5">
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-semibold text-gray-800 truncate">
                                    {{ $hasil->kuis->judul_kuis }}
                                </p>
                                <p class="text-[11px] text-gray-400">{{ $hasil->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-[13px] font-black px-2.5 py-1 rounded-xl {{ $c }} shrink-0">
                                {{ $s }}
                            </span>
                        </div>
                    @empty
                        <div class="py-10 text-center text-gray-400">
                            <p class="text-sm">Belum ada kuis dikerjakan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
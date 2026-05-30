<x-app-layout>
<div class="max-w-4xl mx-auto px-4 pb-10">

    {{-- HEADER --}}
    <div class="mb-8">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500 mb-1">Aktivitas</p>
            <h1 class="text-[28px] font-extrabold tracking-tight text-gray-900 leading-none">Riwayat</h1>
            <p class="text-sm text-gray-400 mt-2">Semua aktivitas belajar kamu</p>
        </div>
        <div class="mt-5 h-px bg-gradient-to-r from-indigo-100 via-gray-200 to-transparent"></div>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-cloak x-init="setTimeout(() => show = false, 3000)"
        class="mb-5 flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-4 py-3 rounded-2xl">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- STATS --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-100 rounded-2xl p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-3">Total Aktivitas</p>
            <p class="text-3xl font-black text-gray-900">{{ $semua->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">aktivitas</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-3">Materi</p>
            <p class="text-3xl font-black text-indigo-600">{{ $materis->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">diupload</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-3">Kuis</p>
            <p class="text-3xl font-black text-emerald-600">{{ $hasilKuis->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">dikerjakan</p>
        </div>
    </div>

    {{-- GLOBAL MODAL — di luar semua loop & transform --}}
    <div x-data="riwayatModal()"
         x-on:open-modal.window="open($event.detail)"
         x-cloak>

        {{-- Backdrop + Modal --}}
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4"
             @click.self="showModal = false">

            <div x-show="showModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white rounded-[24px] shadow-2xl p-6 w-full max-w-sm">

                <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>

                <h3 class="text-center text-[17px] font-bold text-gray-900 mb-1" x-text="title"></h3>
                <p class="text-center text-sm text-gray-500 mb-2" x-text="judul"></p>
                <p class="text-center text-xs text-red-500 bg-red-50 rounded-xl px-4 py-3 mb-5" x-text="warningText"></p>

                <div class="flex gap-3">
                    <button @click="showModal = false"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit"
                            class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-xl transition">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- TABS --}}
    <div x-data="{ tab: 'semua' }" x-cloak>

        <div class="flex gap-2 mb-5">
            @foreach(['semua' => 'Semua', 'materi' => 'Materi', 'kuis' => 'Kuis'] as $key => $label)
            <button @click="tab = '{{ $key }}'"
                :class="tab === '{{ $key }}'
                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200/50'
                    : 'bg-white text-gray-500 border border-gray-100 hover:border-indigo-200 hover:text-indigo-600'"
                class="px-4 py-2 rounded-xl text-[13px] font-semibold transition-all duration-200">
                {{ $label }}
            </button>
            @endforeach
        </div>

        @if($semua->isEmpty())
        <div class="bg-white border border-gray-100 rounded-[26px] py-24 text-center">
            <div class="w-14 h-14 rounded-2xl bg-indigo-600 mx-auto flex items-center justify-center shadow-lg shadow-indigo-200 mb-5">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-[16px] font-bold text-gray-700">Belum ada aktivitas</h3>
            <p class="text-[13px] text-gray-400 mt-1 mb-5">Mulai dengan upload materi pertama kamu</p>
            <a href="{{ route('materi.index') }}"
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[13px] font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-indigo-200/50 transition-all duration-200">
                Upload Materi
            </a>
        </div>
        @else

        {{-- TAB: SEMUA --}}
        <div x-show="tab === 'semua'" class="space-y-2.5">
            @foreach($semua as $item)
            @php
                $deleteRoute = $item['type'] === 'materi'
                    ? route('riwayat.destroyMateri', $item['id'])
                    : route('riwayat.destroyKuis', $item['id']);
                $modalTitle = $item['type'] === 'materi' ? 'Hapus Materi?' : 'Hapus Riwayat?';
                $warningText = $item['type'] === 'materi'
                    ? '⚠️ Materi beserta kuis dan ringkasannya akan ikut terhapus.'
                    : '⚠️ Hasil kuis ini akan dihapus permanen.';
            @endphp
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden
                        hover:border-indigo-200/80 hover:shadow-lg hover:shadow-indigo-50
                        transition-all duration-200">
                <div class="flex items-center gap-4 px-5 py-4">
                    <a href="{{ $item['url'] }}"
                       class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm
                              {{ $item['type'] === 'materi' ? 'bg-indigo-50' : 'bg-emerald-50' }}">
                        @if($item['type'] === 'materi')
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </a>
                    <a href="{{ $item['url'] }}" class="flex-1 min-w-0">
                        <p class="text-[14px] font-bold text-gray-900 truncate">{{ $item['judul'] }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full
                                {{ $item['type'] === 'materi' ? 'bg-indigo-50 text-indigo-500' : 'bg-emerald-50 text-emerald-600' }}">
                                {{ $item['type'] === 'materi' ? 'Materi' : 'Kuis' }}
                            </span>
                            @if($item['type'] === 'kuis')
                                <span class="text-[11px] text-gray-400 truncate">dari {{ $item['materi'] }}</span>
                            @endif
                        </div>
                    </a>
                    <div class="flex items-center gap-3 shrink-0">
                        @if($item['type'] === 'kuis')
                            @php
                                $s = $item['skor'];
                                $sc = $s >= 80 ? 'bg-emerald-50 text-emerald-600' : ($s >= 60 ? 'bg-amber-50 text-amber-500' : 'bg-rose-50 text-rose-500');
                            @endphp
                            <span class="text-[12px] font-black px-2.5 py-1 rounded-xl {{ $sc }}">{{ $s }}</span>
                        @endif
                        <span class="text-[11px] text-gray-400">{{ $item['tanggal']->format('d M Y · H:i') }}</span>
                        <button
                            @click="$dispatch('open-modal', {
                                url: '{{ $deleteRoute }}',
                                judul: '{{ addslashes($item['judul']) }}',
                                title: '{{ $modalTitle }}',
                                warning: '{{ addslashes($warningText) }}'
                            })"
                            class="w-8 h-8 flex items-center justify-center rounded-xl
                                   bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-500
                                   transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- TAB: MATERI --}}
        <div x-show="tab === 'materi'" class="space-y-2.5">
            @forelse($materis as $item)
            <div class="bg-white border border-gray-100 rounded-2xl
                        hover:border-indigo-200/80 hover:shadow-lg hover:shadow-indigo-50
                        transition-all duration-200">
                <div class="flex items-center gap-4 px-5 py-4">
                    <a href="{{ $item['url'] }}" class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </a>
                    <a href="{{ $item['url'] }}" class="flex-1 min-w-0">
                        <p class="text-[14px] font-bold text-gray-900 truncate">{{ $item['judul'] }}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">Upload Materi</p>
                    </a>
                    <span class="text-[11px] text-gray-400 shrink-0">{{ $item['tanggal']->format('d M Y · H:i') }}</span>
                    <button
                        @click="$dispatch('open-modal', {
                            url: '{{ route('riwayat.destroyMateri', $item['id']) }}',
                            judul: '{{ addslashes($item['judul']) }}',
                            title: 'Hapus Materi?',
                            warning: '⚠️ Materi beserta kuis dan ringkasannya akan ikut terhapus.'
                        })"
                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-500 transition shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
            @empty
                <div class="py-14 text-center text-gray-400">
                    <p class="text-sm">Belum ada materi diupload</p>
                </div>
            @endforelse
        </div>

        {{-- TAB: KUIS --}}
        <div x-show="tab === 'kuis'" class="space-y-2.5">
            @forelse($hasilKuis as $item)
            @php
                $s = $item['skor'];
                $sc = $s >= 80 ? 'bg-emerald-50 text-emerald-600' : ($s >= 60 ? 'bg-amber-50 text-amber-500' : 'bg-rose-50 text-rose-500');
            @endphp
            <div class="bg-white border border-gray-100 rounded-2xl
                        hover:border-emerald-200/80 hover:shadow-lg hover:shadow-emerald-50
                        transition-all duration-200">
                <div class="flex items-center gap-4 px-5 py-4">
                    <a href="{{ $item['url'] }}" class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </a>
                    <a href="{{ $item['url'] }}" class="flex-1 min-w-0">
                        <p class="text-[14px] font-bold text-gray-900 truncate">{{ $item['judul'] }}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">dari <span class="text-indigo-500">{{ $item['materi'] }}</span></p>
                    </a>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="text-[12px] font-black px-2.5 py-1 rounded-xl {{ $sc }}">{{ $s }}</span>
                        <span class="text-[11px] text-gray-400">{{ $item['tanggal']->format('d M Y · H:i') }}</span>
                        <button
                            @click="$dispatch('open-modal', {
                                url: '{{ route('riwayat.destroyKuis', $item['id']) }}',
                                judul: '{{ addslashes($item['judul']) }}',
                                title: 'Hapus Riwayat Kuis?',
                                warning: '⚠️ Hasil kuis ini akan dihapus permanen.'
                            })"
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-500 transition shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @empty
                <div class="py-14 text-center text-gray-400">
                    <p class="text-sm">Belum ada kuis dikerjakan</p>
                </div>
            @endforelse
        </div>

        @endif
    </div>

</div>

{{-- Alpine component untuk global modal --}}
<script>
function riwayatModal() {
    return {
        showModal: false,
        deleteUrl: '',
        judul: '',
        title: '',
        warningText: '',
        open(detail) {
            this.deleteUrl  = detail.url;
            this.judul      = detail.judul;
            this.title      = detail.title;
            this.warningText = detail.warning;
            this.showModal  = true;
        }
    }
}
</script>
</x-app-layout>
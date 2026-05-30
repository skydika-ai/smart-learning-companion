{{-- resources/views/materi/index.blade.php --}}
<x-app-layout>

    <style>[x-cloak] { display: none !important; }</style>

    {{-- Wrapper dengan state modal global --}}
    <div x-data="{
        modalOpen: false,
        modalJudul: '',
        modalAction: '',
        openModal(judul, action) {
            this.modalJudul = judul;
            this.modalAction = action;
            this.modalOpen = true;
        }
    }">

        {{-- HEADER --}}
        <div class="flex items-start justify-between mb-7">
            <div>
                <h1 class="text-[28px] font-bold tracking-tight text-gray-900 leading-none">
                    Materi Saya
                </h1>
                <p class="text-sm text-gray-400 mt-1.5">
                    Semua materi pembelajaran yang sudah kamu upload
                </p>
            </div>

            <a href="{{ route('materi.create') }}"
               class="flex items-center gap-2 px-5 py-3 rounded-2xl text-sm font-semibold text-white
                      bg-gradient-to-r from-blue-600 to-indigo-600
                      shadow-lg shadow-blue-200/50
                      hover:-translate-y-0.5 hover:shadow-xl hover:shadow-blue-200/60
                      active:scale-95 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Upload Materi
            </a>
        </div>

        {{-- STATS ROW --}}
        <div class="grid grid-cols-3 gap-4 mb-6">

            <div class="bg-white rounded-2xl border border-gray-100 px-5 py-4">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Materi</p>
                <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $materis->count() }}</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 px-5 py-4">
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Diproses AI</p>
                <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $materis->filter(fn($m) => $m->ringkasan)->count() }}</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 px-5 py-4">
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Menunggu</p>
                <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $materis->filter(fn($m) => !$m->ringkasan)->count() }}</p>
            </div>

        </div>

        {{-- SEARCH --}}
        <div class="bg-white border border-gray-100 rounded-2xl px-4 py-3 flex items-center gap-3 mb-5">
            <svg class="w-5 h-5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
            </svg>
            <input type="text"
                   id="searchInput"
                   placeholder="Cari materi..."
                   class="flex-1 text-sm text-gray-800 placeholder-gray-300 bg-transparent border-none outline-none focus:ring-0">
        </div>

        {{-- LIST --}}
        <div class="flex flex-col gap-3" id="materiList">

            @forelse($materis as $materi)

                <div class="materi-item bg-white rounded-[20px] border border-gray-100
                            flex items-stretch overflow-hidden
                            hover:border-blue-200 hover:shadow-lg hover:shadow-blue-50/80
                            hover:-translate-y-0.5 transition-all duration-200 group"
                     data-judul="{{ strtolower($materi->judul) }}">

                    {{-- Accent bar --}}
                    <div class="w-1.5 shrink-0 {{ $materi->ringkasan ? 'bg-gradient-to-b from-blue-500 to-indigo-600' : 'bg-gradient-to-b from-amber-400 to-orange-400' }}"></div>

                    {{-- Body --}}
                    <div class="flex items-center justify-between gap-4 px-5 py-4 flex-1">

                        {{-- Icon + Info --}}
                        <div class="flex items-center gap-4">
                            <div class="w-[52px] h-[52px] rounded-[16px] shrink-0 flex items-center justify-center
                                        {{ $materi->ringkasan ? 'bg-blue-50' : 'bg-amber-50' }}">
                                <svg class="w-6 h-6 {{ $materi->ringkasan ? 'text-blue-600' : 'text-amber-500' }}"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M7 7v10a2 2 0 002 2h6a2 2 0 002-2V9l-4-4H9a2 2 0 00-2 2z"/>
                                </svg>
                            </div>

                            <div>
                                <h2 class="text-[15px] font-bold text-gray-900 leading-tight mb-1.5">
                                    {{ $materi->judul }}
                                </h2>
                                <div class="flex items-center gap-2">
                                    @if($materi->ringkasan)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold
                                                     bg-green-50 text-green-700 px-2.5 py-0.5 rounded-full">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Selesai diproses
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold
                                                     bg-amber-50 text-amber-600 px-2.5 py-0.5 rounded-full">
                                            <svg class="w-2.5 h-2.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Sedang diproses
                                        </span>
                                    @endif
                                    <span class="text-xs text-gray-400">
                                        {{ $materi->created_at->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 shrink-0">

                            {{-- Detail --}}
                            @if($materi->ringkasan)
                                <a href="{{ route('materi.show', $materi->id) }}"
                                   class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl
                                          bg-blue-50 hover:bg-blue-100 text-blue-700
                                          text-[13px] font-semibold transition-all duration-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                            @else
                                <span class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl
                                             bg-gray-50 text-gray-300 text-[13px] font-semibold cursor-not-allowed">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </span>
                            @endif

                            {{-- Buka File --}}
                            @php
                                $ext = strtolower(pathinfo($materi->file_path, PATHINFO_EXTENSION));
                                $labelFile = match($ext) {
                                    'pdf'  => 'Buka PDF',
                                    'docx' => 'Buka Word',
                                    'txt'  => 'Buka TXT',
                                    default => 'Buka File',
                                };
                            @endphp
                            <a href="{{ asset('storage/' . $materi->file_path) }}"
                               target="_blank"
                               class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl
                                      bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700
                                      text-white text-[13px] font-semibold
                                      shadow-sm shadow-blue-200
                                      transition-all duration-200">
                                {{ $labelFile }}
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>

                            {{-- Tombol Hapus — trigger modal global --}}
                            <button @click="openModal('{{ addslashes($materi->judul) }}', '{{ route('materi.destroy', $materi->id) }}')"
                                    type="button"
                                    class="w-[40px] h-[40px] rounded-xl
                                           bg-red-50 hover:bg-red-100
                                           border border-red-100
                                           flex items-center justify-center
                                           transition-all duration-200">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a1 1 0 011-1h4a1 1 0 011 1v2"/>
                                </svg>
                            </button>

                        </div>

                    </div>

                </div>

            @empty

                <div class="bg-white border-2 border-dashed border-gray-200 rounded-[24px] py-20 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 mx-auto flex items-center justify-center mb-5">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M7 7v10a2 2 0 002 2h6a2 2 0 002-2V9l-4-4H9a2 2 0 00-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Belum ada materi</h3>
                    <p class="text-sm text-gray-400 mb-6">Upload materi pertama kamu sekarang</p>
                    <a href="{{ route('materi.create') }}"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600
                              text-white px-5 py-2.5 rounded-xl text-sm font-semibold
                              shadow-md shadow-blue-200 hover:-translate-y-0.5 transition-all duration-200">
                        Upload Materi
                    </a>
                </div>

            @endforelse

        </div>

        {{-- ========== MODAL HAPUS GLOBAL (di luar loop, di luar card) ========== --}}
        <div x-cloak
             x-show="modalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[999] flex items-center justify-center px-4"
             style="position: fixed;">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"
                 @click="modalOpen = false"></div>

            {{-- Modal box --}}
            <div x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="relative bg-white rounded-[24px] shadow-2xl w-full max-w-sm p-7 z-10">

                {{-- Icon --}}
                <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a1 1 0 011-1h4a1 1 0 011 1v2"/>
                    </svg>
                </div>

                {{-- Text --}}
                <h3 class="text-[18px] font-bold text-gray-900 text-center mb-2">
                    Hapus Materi?
                </h3>
                <p class="text-sm text-gray-400 text-center mb-7 leading-relaxed">
                    Materi <span class="font-semibold text-gray-600" x-text="modalJudul"></span>
                    beserta ringkasan dan kuis-nya akan dihapus permanen.
                </p>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <button @click="modalOpen = false"
                            type="button"
                            class="flex-1 px-4 py-3 rounded-xl bg-gray-100 hover:bg-gray-200
                                   text-sm font-semibold text-gray-600 transition-all duration-200">
                        Batal
                    </button>
                    <button @click="$refs.globalDeleteForm.action = modalAction; $refs.globalDeleteForm.submit()"
                            type="button"
                            class="flex-1 px-4 py-3 rounded-xl
                                bg-gradient-to-r from-red-500 to-rose-600
                                hover:from-red-600 hover:to-rose-700
                                text-white text-sm font-semibold
                                shadow-sm shadow-red-200
                                transition-all duration-200">
                        Ya, Hapus
                    </button>
                </div>

            </div>
        </div>

        {{-- Form global untuk delete (action diisi via Alpine) --}}
        <form x-ref="globalDeleteForm"
              action=""
              method="POST"
              class="hidden">
            @csrf
            @method('DELETE')
        </form>

    </div>{{-- end x-data wrapper --}}

    {{-- SEARCH SCRIPT --}}
    <script>
        document.getElementById('searchInput').addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.materi-item').forEach(el => {
                el.style.display = el.dataset.judul.includes(q) ? '' : 'none';
            });
        });
    </script>

</x-app-layout>
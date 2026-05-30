<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 pb-10">

        {{-- HEADER --}}
        <div class="mb-8">
            <a href="{{ route('materi.index') }}"
               class="inline-flex items-center gap-1.5 text-[13px] text-gray-400 hover:text-indigo-600 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Materi
            </a>
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500 mb-1">Materi Baru</p>
            <h1 class="text-[28px] font-extrabold tracking-tight text-gray-900 leading-none">Upload Materi</h1>
            <p class="text-sm text-gray-400 mt-2">AI akan otomatis membuat ringkasan dan kuis dari file kamu</p>
            <div class="mt-5 h-px bg-gradient-to-r from-indigo-100 via-gray-200 to-transparent"></div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6">

            <form action="{{ route('materi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Judul --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Judul Materi
                    </label>
                    <input type="text" name="judul" value="{{ old('judul') }}"
                           placeholder="Contoh: Algoritma dan Struktur Data"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm
                                  focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                  {{ $errors->has('judul') ? 'border-red-400' : '' }}">
                    @error('judul')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Upload Area --}}
                <div class="mb-6" x-data="{ fileName: '', dragging: false }">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">File Materi</label>

                    <div class="relative border-2 border-dashed rounded-2xl p-10 text-center transition-all duration-200 cursor-pointer"
                         :class="dragging ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200 hover:border-indigo-300 hover:bg-gray-50/50'"
                         @dragover.prevent="dragging = true"
                         @dragleave.prevent="dragging = false"
                         @drop.prevent="dragging = false; fileName = $event.dataTransfer.files[0]?.name"
                         @click="$refs.fileInput.click()">

                        {{-- Icon --}}
                        <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center transition-all duration-200"
                             :class="fileName ? 'bg-indigo-100' : 'bg-gray-100'">
                            <svg class="w-8 h-8 transition-colors duration-200"
                                 :class="fileName ? 'text-indigo-600' : 'text-gray-400'"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>

                        {{-- Text --}}
                        <div x-show="!fileName">
                            <p class="text-[15px] font-semibold text-gray-700 mb-1">Drag & drop file di sini</p>
                            <p class="text-sm text-gray-400 mb-4">atau klik untuk memilih file</p>
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-semibold shadow-md shadow-indigo-200/50 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                Pilih File
                            </span>
                            <p class="text-xs text-gray-400 mt-4">PDF atau DOCX · Maks. 10MB</p>
                        </div>

                        {{-- File terpilih --}}
                        <div x-show="fileName" x-cloak>
                            <div class="inline-flex items-center gap-3 bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-indigo-700 truncate max-w-xs" x-text="fileName"></p>
                                <button type="button"
                                        @click.stop="fileName = ''; $refs.fileInput.value = ''"
                                        class="w-5 h-5 rounded-full bg-indigo-200 hover:bg-indigo-300 text-indigo-600 flex items-center justify-center text-xs font-bold transition-colors shrink-0">
                                    ×
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 mt-3">Klik untuk ganti file</p>
                        </div>

                        <input x-ref="fileInput" type="file" name="file"
                               accept=".pdf,.doc,.docx" class="hidden"
                               @change="fileName = $event.target.files[0]?.name">
                    </div>

                    @error('file')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info AI --}}
                <div class="flex items-start gap-3 bg-indigo-50 border border-indigo-100 rounded-2xl px-4 py-3.5 mb-6">
                    <div class="w-8 h-8 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-indigo-700">Diproses oleh AI</p>
                        <p class="text-xs text-indigo-500 mt-0.5 leading-relaxed">
                            Setelah upload, AI akan membuat ringkasan dan 5 soal kuis secara otomatis. Proses membutuhkan beberapa saat.
                        </p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="flex items-center gap-2 px-6 py-2.5 rounded-xl
                                   bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                                   shadow-md shadow-indigo-200/50 hover:-translate-y-0.5
                                   transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Upload Sekarang
                    </button>
                    <a href="{{ route('materi.index') }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-500
                              bg-gray-50 hover:bg-gray-100 border border-gray-100
                              transition-all duration-200">
                        Batal
                    </a>
                </div>

            </form>
        </div>

    </div>

    <style>
        [x-cloak] { display: none !important; }
        input[type="text"]:focus {
            outline: none;
        }
    </style>

</x-app-layout>
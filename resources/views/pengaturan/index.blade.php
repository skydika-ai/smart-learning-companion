<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 pb-10">

        <!-- HEADER -->
        <div class="mb-6">
            <h1 class="text-[26px] font-bold text-gray-800 tracking-tight">Pengaturan</h1>
            <p class="text-[13px] text-gray-400 mt-1">Kelola keamanan dan privasi akun kamu</p>
        </div>

        {{-- Flash: password updated --}}
        @if (session('status') === 'password-updated')
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="flex items-center gap-3 mb-5 bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-5 py-4 rounded-2xl shadow-sm">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Password berhasil diperbarui.
            </div>
        @endif

        {{-- Section: Ubah Password --}}
        <div class="bg-white border border-gray-100 rounded-[26px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 mb-5">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-400 flex items-center justify-center shadow-md">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-[15px] font-semibold text-gray-800">Ubah Password</h3>
                    <p class="text-[12px] text-gray-400">Pastikan password kamu kuat dan aman</p>
                </div>
            </div>

            <form method="POST" action="{{ route('pengaturan.password') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                {{-- Password Saat Ini --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="current_password"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 pr-11 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->updatePassword->has('current_password') ? 'border-red-400' : '' }}"
                            placeholder="••••••••" autocomplete="current-password">
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95M6.938 6.938A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.38 2.62M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    {{-- Error + Lupa Password --}}
                    <div class="flex justify-between items-start mt-1">
                        <div>
                            @if ($errors->updatePassword->has('current_password'))
                                <p class="text-xs text-red-500">{{ $errors->updatePassword->first('current_password') }}</p>
                            @endif
                        </div>
                        <div x-data="{ tipOpen: false }" class="relative">
                            <button type="button" @click="tipOpen = !tipOpen"
                                class="text-xs text-blue-500 hover:text-blue-700 hover:underline transition-colors whitespace-nowrap">
                                Lupa password?
                            </button>
                            <div x-show="tipOpen" x-cloak @click.outside="tipOpen = false"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute right-0 top-6 z-10 w-64 bg-white border border-gray-100 rounded-2xl shadow-lg p-4">
                                <p class="text-xs text-gray-500 leading-relaxed">
                                    Untuk mereset password, kamu perlu
                                    <strong class="text-gray-700">logout</strong> terlebih dahulu,
                                    lalu klik <strong class="text-gray-700">"Lupa password?"</strong>
                                    di halaman login.
                                </p>
                                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-xs font-semibold text-white bg-gradient-to-r from-indigo-500 to-blue-400 hover:opacity-90 rounded-xl px-3 py-2 transition-opacity">
                                        Logout & Reset Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Password Baru --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 pr-11 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->updatePassword->has('password') ? 'border-red-400' : '' }}"
                            placeholder="••••••••" autocomplete="new-password">
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95M6.938 6.938A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.38 2.62M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    @if ($errors->updatePassword->has('password'))
                        <p class="mt-1 text-xs text-red-500">{{ $errors->updatePassword->first('password') }}</p>
                    @endif
                </div>

                {{-- Konfirmasi Password Baru --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 pr-11 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="••••••••" autocomplete="new-password">
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95M6.938 6.938A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.38 2.62M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="pt-1">
                    <button type="submit"
                        class="px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-blue-400 hover:opacity-90 text-white text-sm font-semibold rounded-xl transition-opacity shadow-md">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>

        {{-- Section: Hapus Akun --}}
        <div class="bg-white border border-red-100 rounded-[26px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6"
             x-data="{ modalOpen: false }">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-red-400 to-rose-500 flex items-center justify-center shadow-md">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-[15px] font-semibold text-red-600">Hapus Akun</h3>
                    <p class="text-[12px] text-gray-400">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            <p class="text-sm text-gray-500 mb-4">
                Setelah akun dihapus, seluruh data kamu termasuk materi, kuis, dan riwayat akan dihapus secara permanen.
            </p>

            <button type="button" @click="modalOpen = true"
                class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-rose-500 hover:opacity-90 text-white text-sm font-semibold rounded-xl transition-opacity shadow-md">
                Hapus Akun Saya
            </button>

            {{-- Modal Konfirmasi --}}
            <div x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
                 style="display: none;">
                <div x-show="modalOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="bg-white rounded-[24px] shadow-2xl w-full max-w-md p-6">

                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-[16px] font-bold text-gray-900">Hapus Akun?</h3>
                    </div>

                    <p class="text-sm text-gray-500 mb-5">
                        Masukkan password kamu untuk mengonfirmasi. Semua data akan dihapus permanen dan tidak bisa dipulihkan.
                    </p>

                    <form method="POST" action="{{ route('pengaturan.destroy') }}">
                        @csrf
                        @method('DELETE')

                        <div class="mb-4">
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 pr-11 text-sm focus:ring-2 focus:ring-red-400 focus:border-transparent {{ $errors->userDeletion->has('password') ? 'border-red-400' : '' }}"
                                    placeholder="Password kamu" autocomplete="current-password">
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95M6.938 6.938A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.38 2.62M3 3l18 18"/>
                                    </svg>
                                </button>
                            </div>
                            @if ($errors->userDeletion->has('password'))
                                <p class="mt-1 text-xs text-red-500">{{ $errors->userDeletion->first('password') }}</p>
                            @endif
                        </div>

                        <div class="flex gap-3 justify-end">
                            <button type="button" @click="modalOpen = false"
                                class="px-4 py-2 text-sm text-gray-600 font-medium rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm text-white font-semibold rounded-xl bg-gradient-to-r from-red-500 to-rose-500 hover:opacity-90 transition-opacity shadow-md">
                                Ya, Hapus Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <style>
        [x-cloak] { display: none !important; }
        input[type="password"], input[type="text"] {
            border-radius: 12px !important;
            font-size: 14px !important;
        }
        input[type="password"]:focus, input[type="text"]:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1) !important;
            outline: none !important;
        }
    </style>
</x-app-layout>
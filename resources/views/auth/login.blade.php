<x-guest-layout>

    <!-- Header -->
    <div class="text-center mb-7">
        <div class="inline-flex items-center gap-2 bg-indigo-50 border border-indigo-100 px-3.5 py-1.5 rounded-full mb-4">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
            </span>
            <span class="text-[11px] font-bold text-indigo-700 tracking-wide uppercase">Selamat Datang Kembali</span>
        </div>
        <h2 class="text-[26px] font-extrabold text-slate-900 leading-tight mb-1">
            Lanjutkan <span class="shimmer-text">Belajarmu</span>
        </h2>
        <p class="text-[13px] text-slate-400 font-medium">Masuk untuk mengakses materi & kuis kamu</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-5">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   placeholder="email@kamu.com"
                   class="w-full border border-gray-200 rounded-2xl px-5 py-3.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-blue-500
                          bg-white text-gray-700 transition-all duration-150
                          {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}"
                   required autofocus autocomplete="username">
            @error('email')
                <p class="mt-1 text-xs font-semibold text-red-500">⚠ {{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-5" x-data="{ show: false }">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
            <div class="relative">
                <input id="password"
                       :type="show ? 'text' : 'password'"
                       name="password"
                       placeholder="Masukkan password"
                       class="w-full border border-gray-200 rounded-2xl px-5 py-3.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500
                              bg-white pr-12 text-gray-700 transition-all duration-150
                              {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}"
                       required autocomplete="current-password">
                <button type="button" @click="show = !show"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-xs font-semibold text-red-500">⚠ {{ $message }}</p>
            @enderror
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember"
                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="text-sm text-slate-500 font-medium">Ingat saya</span>
            </label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-colors">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary">
            Masuk Sekarang
            <svg class="w-4 h-4 inline-block ml-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </button>

        <!-- Divider -->
        <div class="divider mt-5 mb-4">atau</div>

        <!-- Register link -->
        <p class="text-center text-sm text-slate-400">
            Belum punya akun?
            <a href="{{ route('register') }}"
               class="text-blue-600 hover:text-blue-700 hover:underline font-semibold ml-1">
                Daftar gratis →
            </a>
        </p>

    </form>

</x-guest-layout>
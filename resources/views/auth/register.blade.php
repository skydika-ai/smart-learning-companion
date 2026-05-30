<x-guest-layout>

    <!-- Header -->
    <div class="text-center mb-7">
        <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-100 px-3.5 py-1.5 rounded-full mb-4">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
            </span>
            <span class="text-[11px] font-bold text-blue-700 tracking-wide uppercase">Buat Akun Baru</span>
        </div>
        <h2 class="text-[26px] font-extrabold text-slate-900 leading-tight mb-1">
            Mulai Belajar <span class="shimmer-text">Lebih Cerdas</span>
        </h2>
        <p class="text-[13px] text-slate-400 font-medium">Gratis selamanya · Tidak perlu kartu kredit</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-5">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   placeholder="Nama kamu"
                   class="w-full border border-gray-200 rounded-2xl px-5 py-3.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-blue-500
                          bg-white text-gray-700 transition-all duration-150
                          {{ $errors->has('name') ? 'border-red-400 bg-red-50' : '' }}"
                   required autofocus autocomplete="name">
            @error('name')
                <p class="mt-1 text-xs font-semibold text-red-500">⚠ {{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-5">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   placeholder="email@kamu.com"
                   class="w-full border border-gray-200 rounded-2xl px-5 py-3.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-blue-500
                          bg-white text-gray-700 transition-all duration-150
                          {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}"
                   required autocomplete="username">
            @error('email')
                <p class="mt-1 text-xs font-semibold text-red-500">⚠ {{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-5" x-data="{ show: false }">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
            <div class="relative">
                <input id="password" :type="show ? 'text' : 'password'"
                       name="password"
                       placeholder="Min. 8 karakter"
                       class="w-full border border-gray-200 rounded-2xl px-5 py-3.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500
                              bg-white pr-12 text-gray-700 transition-all duration-150
                              {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}"
                       required autocomplete="new-password">
                <button type="button" @click="show = !show"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-xs font-semibold text-red-500">⚠ {{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6" x-data="{ show: false }">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password</label>
            <div class="relative">
                <input id="password_confirmation" :type="show ? 'text' : 'password'"
                       name="password_confirmation"
                       placeholder="Ulangi password"
                       class="w-full border border-gray-200 rounded-2xl px-5 py-3.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500
                              bg-white pr-12 text-gray-700 transition-all duration-150
                              {{ $errors->has('password_confirmation') ? 'border-red-400 bg-red-50' : '' }}"
                       required autocomplete="new-password">
                <button type="button" @click="show = !show"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password_confirmation')
                <p class="mt-1 text-xs font-semibold text-red-500">⚠ {{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary">
            Buat Akun — Gratis
            <svg class="w-4 h-4 inline-block ml-2 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </button>

        <!-- Divider -->
        <div class="divider mt-5 mb-4">atau</div>

        <!-- Login link -->
        <p class="text-center text-sm text-slate-400">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 hover:underline font-semibold ml-1">
                Masuk sekarang →
            </a>
        </p>

    </form>

    <!-- Trust badges -->
    <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-center gap-5">
        @foreach(['Gratis', 'Aman', 'Tanpa Kartu Kredit'] as $badge)
        <div class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-400">
            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            {{ $badge }}
        </div>
        @endforeach
    </div>

</x-guest-layout>
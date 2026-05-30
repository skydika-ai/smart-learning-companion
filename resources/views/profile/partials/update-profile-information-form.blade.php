<form method="post" action="{{ route('profile.update') }}" class="space-y-4">
    @csrf
    @method('patch')

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Nama --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
        <input id="name" name="name" type="text"
               value="{{ old('name', $user->name) }}"
               required autofocus autocomplete="name"
               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm
                      focus:ring-2 focus:ring-blue-500 focus:border-transparent
                      {{ $errors->has('name') ? 'border-red-400' : '' }}">
        @error('name')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input id="email" name="email" type="email"
               value="{{ old('email', $user->email) }}"
               required autocomplete="username"
               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm
                      focus:ring-2 focus:ring-blue-500 focus:border-transparent
                      {{ $errors->has('email') ? 'border-red-400' : '' }}">
        @error('email')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2 p-3 bg-amber-50 border border-amber-100 rounded-xl">
                <p class="text-xs text-amber-700">
                    Email kamu belum diverifikasi.
                    <button form="send-verification"
                            class="underline font-semibold hover:text-amber-900 transition-colors">
                        Kirim ulang email verifikasi
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-1 text-xs text-green-600 font-medium">
                        Link verifikasi telah dikirim ke email kamu.
                    </p>
                @endif
            </div>
        @endif
    </div>

    {{-- Submit --}}
    <div class="flex items-center gap-3 pt-1">
        <button type="submit"
                class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500
                       hover:opacity-90 text-white text-sm font-semibold
                       rounded-xl shadow-md shadow-blue-200/50 transition-opacity">
            Simpan Perubahan
        </button>
        @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }"
               x-show="show" x-transition
               x-init="setTimeout(() => show = false, 2500)"
               class="text-sm text-emerald-600 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Tersimpan!
            </p>
        @endif
    </div>

</form>
<x-app-layout>
<div class="max-w-3xl mx-auto px-4 pb-10">

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-[26px] font-bold text-gray-800 tracking-tight">Profil Saya</h1>
        <p class="text-[13px] text-gray-400 mt-1">Kelola informasi identitas akun kamu</p>
    </div>

    <!-- AVATAR + INFO -->
    <div class="bg-white border border-gray-100 rounded-[26px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 mb-5 flex items-center gap-5">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white text-2xl font-bold shadow-lg flex-shrink-0">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="flex-1">
            <h2 class="text-[17px] font-bold text-gray-800">{{ auth()->user()->name }}</h2>
            <p class="text-[13px] text-gray-400 mt-[2px]">{{ auth()->user()->email }}</p>
            <span class="inline-block mt-2 text-[11px] font-semibold px-3 py-1 rounded-full
                {{ auth()->user()->role === 'admin' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600' }}">
                {{ ucfirst(auth()->user()->role) }}
            </span>
        </div>
        <!-- Shortcut ke Pengaturan -->
        <a href="{{ route('pengaturan.index') }}"
           class="flex items-center gap-2 text-[12px] text-gray-400 hover:text-blue-600 transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Pengaturan
        </a>
    </div>

    <!-- UPDATE PROFILE INFO -->
    <div class="bg-white border border-gray-100 rounded-[26px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-md">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-[15px] font-semibold text-gray-800">Informasi Akun</h3>
                <p class="text-[12px] text-gray-400">Perbarui nama dan email kamu</p>
            </div>
        </div>
        @include('profile.partials.update-profile-information-form')
    </div>

    <!-- HINT ke Pengaturan -->
    <div class="mt-4 flex items-center gap-2 text-[12px] text-gray-400 px-2">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Untuk mengubah password atau menghapus akun, buka
        <a href="{{ route('pengaturan.index') }}" class="text-blue-500 hover:underline font-medium">Pengaturan</a>
    </div>

</div>

<style>
    .profil-form input[type="text"],
    .profil-form input[type="email"] {
        border-radius: 12px !important;
        border-color: #e5e7eb !important;
        font-size: 14px !important;
        padding: 10px 14px !important;
    }
    .profil-form input[type="text"]:focus,
    .profil-form input[type="email"]:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1) !important;
        outline: none !important;
    }
</style>
</x-app-layout>
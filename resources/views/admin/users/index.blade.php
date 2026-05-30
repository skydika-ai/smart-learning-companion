<x-app-layout>

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kelola User</h1>
        <p class="text-sm text-gray-500">Blokir atau aktifkan akun user terdaftar</p>
    </div>

    <!-- STATS MINI -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $totalUser }}</p>
                <p class="text-xs text-gray-400">Total User</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $totalAktif }}</p>
                <p class="text-xs text-gray-400">User Aktif</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $totalBlokir }}</p>
                <p class="text-xs text-gray-400">Diblokir</p>
            </div>
        </div>
    </div>

    <!-- SEARCH -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4 mb-4">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="relative flex gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="search"
                        id="searchInput"
                        value="{{ $search }}"
                        placeholder="Cari nama atau email user..."
                        class="w-full pl-10 pr-10 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                        autocomplete="off"
                    />
                    @if($search)
                    <a href="{{ route('admin.users.index') }}"
                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                    @endif
                </div>
                <button type="submit"
                    class="px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-xl transition">
                    Cari
                </button>
            </div>
        </form>

        @if($search)
        <p class="text-xs text-gray-400 mt-2">
            Hasil pencarian untuk <span class="font-semibold text-gray-600">"{{ $search }}"</span>
            — {{ $users->total() }} user ditemukan
        </p>
        @endif
    </div>

    <!-- TABEL -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="text-xs text-gray-400 uppercase bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 font-medium tracking-wide">Nama</th>
                    <th class="px-6 py-4 font-medium tracking-wide">Email</th>
                    <th class="px-6 py-4 font-medium tracking-wide">Role</th>
                    <th class="px-6 py-4 font-medium tracking-wide">Status</th>
                    <th class="px-6 py-4 font-medium tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr
                    class="hover:bg-gray-50/60 transition"
                    x-data="{
                        isActive: {{ $user->is_active ? 'true' : 'false' }},
                        showModal: false,
                        loading: false,
                        async doToggle() {
                            this.showModal = false;
                            this.loading = true;
                            try {
                                const res = await fetch('/admin/users/{{ $user->id }}/toggle', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({ _method: 'PATCH' })
                                });
                                const data = await res.json();
                                if (data.success) this.isActive = data.is_active;
                            } catch(e) { console.error(e); }
                            finally { this.loading = false; }
                        }
                    }"
                >
                    <!-- Nama -->
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                        </div>
                    </td>

                    <!-- Email -->
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $user->email }}</td>

                    <!-- Role -->
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-lg bg-blue-50 text-blue-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            User
                        </span>
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4">
                        <span
                            class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full"
                            :class="isActive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'"
                        >
                            <span class="w-1.5 h-1.5 rounded-full" :class="isActive ? 'bg-green-500' : 'bg-red-400'"></span>
                            <span x-text="isActive ? 'Aktif' : 'Diblokir'"></span>
                        </span>
                    </td>

                    <!-- Aksi -->
                    <td class="px-6 py-4">
                        <div class="relative group inline-block">
                            <button
                                @click="showModal = true"
                                :disabled="loading"
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold transition"
                                :class="isActive ? 'bg-red-50 text-red-500 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100'"
                            >
                                <svg x-show="loading" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                <svg x-show="!loading && isActive" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                <svg x-show="!loading && !isActive" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span x-text="loading ? 'Memproses...' : (isActive ? 'Blokir' : 'Aktifkan')"></span>
                            </button>

                            <!-- Tooltip -->
                            <span class="absolute -top-9 left-1/2 -translate-x-1/2 whitespace-nowrap text-xs bg-gray-800 text-white px-2.5 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition pointer-events-none z-10"
                                x-text="isActive ? 'Klik untuk memblokir user ini' : 'Klik untuk mengaktifkan user ini'">
                            </span>
                        </div>

                        <!-- Modal Konfirmasi -->
                        <div
                            x-show="showModal"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
                            @click.self="showModal = false"
                            style="display:none"
                        >
                            <div
                                x-show="showModal"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4"
                            >
                                <div class="flex justify-center mb-4">
                                    <div class="w-16 h-16 rounded-full flex items-center justify-center"
                                        :class="isActive ? 'bg-red-100' : 'bg-green-100'">
                                        <svg x-show="isActive" class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        <svg x-show="!isActive" class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>

                                <h3 class="text-center text-lg font-bold mb-1"
                                    :class="isActive ? 'text-red-600' : 'text-green-600'"
                                    x-text="isActive ? 'Blokir User?' : 'Aktifkan User?'">
                                </h3>

                                <div class="flex items-center justify-center gap-2 mb-1">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $user->name }}</p>
                                </div>
                                <p class="text-center text-xs text-gray-400 mb-3">{{ $user->email }}</p>

                                <div class="rounded-xl px-4 py-3 mb-5" :class="isActive ? 'bg-red-50' : 'bg-green-50'">
                                    <p class="text-center text-xs" :class="isActive ? 'text-red-600' : 'text-green-700'">
                                        <span x-show="isActive">⚠️ User tidak bisa login setelah diblokir. Yakin ingin melanjutkan?</span>
                                        <span x-show="!isActive">✅ User bisa login kembali setelah diaktifkan. Yakin ingin melanjutkan?</span>
                                    </p>
                                </div>

                                <div class="flex gap-3">
                                    <button @click="showModal = false"
                                        class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                                        Batal
                                    </button>
                                    <button @click="doToggle()"
                                        class="flex-1 px-4 py-2.5 text-sm font-medium text-white rounded-xl transition"
                                        :class="isActive ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600'"
                                        x-text="isActive ? 'Ya, Blokir' : 'Ya, Aktifkan'">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                            <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm">{{ $search ? 'User tidak ditemukan.' : 'Belum ada user terdaftar.' }}</p>
                            @if($search)
                            <a href="{{ route('admin.users.index') }}" class="text-xs text-blue-500 hover:underline mt-1">Reset pencarian</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-400">
                Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user
            </p>
            <div class="flex items-center gap-1">
                {{-- Prev --}}
                @if($users->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                @endif

                {{-- Page numbers --}}
                @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    @if($page == $users->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-500 text-white text-xs font-semibold">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 transition text-xs">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                @endif
            </div>
        </div>
        @endif
    </div>

</x-app-layout>
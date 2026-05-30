<x-app-layout>

    <!-- Chart.js — load PALING ATAS sebelum semua script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

    <!-- HEADER + LIVE INDICATOR -->
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Selamat datang, Admin! 🔥</h1>
            <p class="text-sm text-gray-400">Berikut ringkasan aktivitas sistem.</p>
        </div>
        <div class="flex items-center gap-2 bg-white border border-gray-100 px-3 py-1.5 rounded-full shadow-sm mt-1">
            <span id="live-dot" class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
            <span id="live-text" class="text-xs font-semibold text-gray-500">Live</span>
            <span id="last-updated" class="text-xs text-gray-400"></span>
        </div>
    </div>

    <!-- STATS CARDS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <!-- Total User -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-400 font-medium">Total User</p>
            </div>
            <p class="text-3xl font-bold text-gray-800" id="stat-total-users">{{ number_format($totalUser) }}</p>
            <p class="text-xs mt-2 {{ $userPct >= 0 ? 'text-green-500' : 'text-red-500' }}" id="stat-user-growth">
                {{ $userPct >= 0 ? '+' : '' }}{{ $userPct }}% dari bulan lalu
            </p>
        </div>

        <!-- Total Dokumen -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-400 font-medium">Total Dokumen</p>
            </div>
            <p class="text-3xl font-bold text-gray-800" id="stat-total-dokumen">{{ number_format($totalMateri) }}</p>
            <p class="text-xs mt-2 {{ $materiPct >= 0 ? 'text-green-500' : 'text-red-500' }}" id="stat-dokumen-growth">
                {{ $materiPct >= 0 ? '+' : '' }}{{ $materiPct }}% dari bulan lalu
            </p>
        </div>

        <!-- Kuis Dikerjakan -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-400 font-medium">Kuis Dikerjakan</p>
            </div>
            <p class="text-3xl font-bold text-gray-800" id="stat-total-kuis">{{ number_format($totalKuis) }}</p>
            <p class="text-xs mt-2 {{ $kuisPct >= 0 ? 'text-green-500' : 'text-red-500' }}" id="stat-kuis-growth">
                {{ $kuisPct >= 0 ? '+' : '' }}{{ $kuisPct }}% dari bulan lalu
            </p>
        </div>

        <!-- Aktivitas Hari Ini -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-11 h-11 rounded-xl bg-orange-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-400 font-medium">Aktivitas Hari Ini</p>
            </div>
            <p class="text-3xl font-bold text-gray-800" id="stat-today-activity">{{ number_format($aktivitasHariIni) }}</p>
            <p class="text-xs mt-2 {{ $aktivitasPct >= 0 ? 'text-green-500' : 'text-red-500' }}" id="stat-activity-growth">
                {{ $aktivitasPct >= 0 ? '+' : '' }}{{ $aktivitasPct }}% dari kemarin
            </p>
        </div>

    </div>

    <!-- BOTTOM GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

        <!-- LEFT: Aktivitas Terbaru + User Terbaru -->
        <div class="lg:col-span-7 space-y-4">

            <!-- Aktivitas Terbaru -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="text-base font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>
                <div class="space-y-3" id="activities-list">
                    @forelse($aktivitasTerbaru as $item)
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0
                                {{ $item->icon === 'upload' ? 'bg-blue-50' : ($item->icon === 'kuis' ? 'bg-purple-50' : 'bg-green-50') }}">
                                @if($item->icon === 'upload')
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                @elseif($item->icon === 'kuis')
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold text-gray-800">{{ $item->user->name ?? '-' }}</span>
                                {{ $item->aksi }}
                                @if($item->target)
                                    <span class="font-semibold text-blue-600">{{ $item->target }}</span>
                                @endif
                            </p>
                        </div>
                        <p class="text-xs text-gray-400 shrink-0">
                            {{ \Carbon\Carbon::parse($item->waktu)->format('d M Y, H:i') }}
                        </p>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">Belum ada aktivitas.</p>
                    @endforelse
                </div>
            </div>

            <!-- User Terbaru -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-gray-800">User Terbaru</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-xs text-blue-500 hover:underline">Lihat semua →</a>
                </div>
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-xs text-gray-400 border-b border-gray-100">
                            <th class="pb-3 font-medium">Nama</th>
                            <th class="pb-3 font-medium">Email</th>
                            <th class="pb-3 font-medium">Role</th>
                            <th class="pb-3 font-medium">Status</th>
                            <th class="pb-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" id="recent-users-body">
                        @foreach($recentUsers as $user)
                        <tr
                            class="hover:bg-gray-50/50"
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
                            <td class="py-3 font-medium text-gray-700">{{ $user->name }}</td>
                            <td class="py-3 text-gray-500 text-xs">{{ $user->email }}</td>
                            <td class="py-3 text-gray-500 capitalize text-xs">{{ $user->role }}</td>
                            <td class="py-3">
                                <span
                                    class="text-xs font-semibold"
                                    :class="isActive ? 'text-green-500' : 'text-red-500'"
                                    x-text="isActive ? 'Aktif' : 'Diblokir'"
                                ></span>
                            </td>
                            <td class="py-3">
                                <div class="flex items-center gap-2">

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.users.index') }}"
                                       title="Kelola User"
                                       class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>

                                    {{-- Tombol Blokir/Aktifkan --}}
                                    @if($user->role !== 'admin')
                                    <div class="relative group">
                                        <button
                                            @click="showModal = true"
                                            :disabled="loading"
                                            class="w-7 h-7 rounded-full flex items-center justify-center transition"
                                            :class="isActive ? 'bg-red-50 hover:bg-red-100' : 'bg-green-50 hover:bg-green-100'"
                                            :title="isActive ? 'Blokir User' : 'Aktifkan User'"
                                        >
                                            {{-- Icon Blokir --}}
                                            <svg x-show="!loading && isActive" class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                            {{-- Icon Aktifkan --}}
                                            <svg x-show="!loading && !isActive" class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{-- Spinner --}}
                                            <svg x-show="loading" class="w-3.5 h-3.5 animate-spin text-gray-400" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                            </svg>
                                        </button>

                                        {{-- Tooltip --}}
                                        <span
                                            class="absolute -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap text-xs bg-gray-800 text-white px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition pointer-events-none z-10"
                                            x-text="isActive ? 'Blokir User' : 'Aktifkan User'"
                                        ></span>
                                    </div>

                                    {{-- Modal Konfirmasi --}}
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
                                            {{-- Icon --}}
                                            <div class="flex justify-center mb-4">
                                                <div class="w-14 h-14 rounded-full flex items-center justify-center"
                                                    :class="isActive ? 'bg-red-100' : 'bg-green-100'">
                                                    <svg x-show="isActive" class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                    </svg>
                                                    <svg x-show="!isActive" class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>
                                            </div>

                                            {{-- Judul --}}
                                            <h3
                                                class="text-center text-base font-semibold mb-1"
                                                :class="isActive ? 'text-red-600' : 'text-green-600'"
                                                x-text="isActive ? 'Blokir User ini?' : 'Aktifkan User ini?'"
                                            ></h3>

                                            {{-- Nama User --}}
                                            <p class="text-center text-sm font-bold text-gray-800 mb-1">{{ $user->name }}</p>

                                            {{-- Pesan --}}
                                            <p class="text-center text-xs text-gray-400 mb-6">
                                                <span x-show="isActive">User tidak bisa login setelah diblokir. Yakin ingin melanjutkan?</span>
                                                <span x-show="!isActive">User bisa login kembali setelah diaktifkan. Yakin ingin melanjutkan?</span>
                                            </p>

                                            {{-- Tombol --}}
                                            <div class="flex gap-3">
                                                <button
                                                    @click="showModal = false"
                                                    class="flex-1 px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition"
                                                >
                                                    Batal
                                                </button>
                                                <button
                                                    @click="doToggle()"
                                                    class="flex-1 px-4 py-2 text-sm font-medium text-white rounded-xl transition"
                                                    :class="isActive ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600'"
                                                    x-text="isActive ? 'Ya, Blokir' : 'Ya, Aktifkan'"
                                                ></button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <!-- RIGHT: Grafik Pengguna -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 h-full">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-base font-semibold text-gray-800">Grafik Pengguna</h2>
                    <span class="text-xs text-gray-400 border border-gray-200 rounded-lg px-3 py-1.5">7 Hari Terakhir ↓</span>
                </div>
                <div class="relative" style="height: 240px;">
                    <canvas id="grafik-pengguna"></canvas>
                </div>
            </div>
        </div>

    </div>

    <script>
    (function () {
        const POLL_MS  = 10000;
        const ENDPOINT = "{{ route('admin.dashboard.realtime') }}";
        const CSRF     = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        let chart = null;

        // ── Tunggu Chart.js siap ──
        function waitForChart(cb) {
            if (typeof Chart !== 'undefined') { cb(); return; }
            const t = setInterval(() => {
                if (typeof Chart !== 'undefined') { clearInterval(t); cb(); }
            }, 50);
        }

        function initChart(labels, data) {
            const ctx = document.getElementById('grafik-pengguna');
            if (!ctx) return;
            if (chart) chart.destroy();
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Pengguna Baru',
                        data,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59,130,246,0.08)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#3B82F6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.4,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#94A3B8', font: { size: 11 } },
                            grid: { color: 'rgba(0,0,0,0.04)' }
                        },
                        x: {
                            ticks: { color: '#94A3B8', font: { size: 11 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        function updateChart(labels, data) {
            if (!chart) { initChart(labels, data); return; }
            chart.data.labels = labels;
            chart.data.datasets[0].data = data;
            chart.update('active');
        }

        // ── Stats ──
        function animateValue(id, newVal) {
            const el = document.getElementById(id);
            if (!el || el.textContent.trim() === String(newVal)) return;
            el.style.transition = 'opacity .25s';
            el.style.opacity = '0';
            setTimeout(() => { el.textContent = newVal; el.style.opacity = '1'; }, 250);
        }

        function updateStats(s) {
            animateValue('stat-total-users',    s.total_users);
            animateValue('stat-total-dokumen',  s.total_dokumen);
            animateValue('stat-total-kuis',     s.total_kuis);
            animateValue('stat-today-activity', s.today_activity);

            const growthMap = {
                'stat-user-growth'     : { val: s.user_growth,    suffix: '% dari bulan lalu' },
                'stat-dokumen-growth'  : { val: s.dokumen_growth,  suffix: '% dari bulan lalu' },
                'stat-kuis-growth'     : { val: s.kuis_growth,     suffix: '% dari bulan lalu' },
                'stat-activity-growth' : { val: s.activity_growth, suffix: '% dari kemarin' },
            };
            Object.entries(growthMap).forEach(([id, g]) => {
                const el = document.getElementById(id);
                if (!el) return;
                const prefix = g.val >= 0 ? '+' : '';
                el.textContent = prefix + g.val + g.suffix;
                el.className = 'text-xs mt-2 ' + (g.val >= 0 ? 'text-green-500' : 'text-red-500');
            });
        }

        // ── Aktivitas ──
        function updateActivities(activities) {
            const container = document.getElementById('activities-list');
            if (!container) return;
            if (!activities.length) {
                container.innerHTML = '<p class="text-sm text-gray-400">Belum ada aktivitas.</p>';
                return;
            }
            const iconMap = {
                upload  : { bg: 'bg-blue-50',   color: 'text-blue-500',   path: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
                kuis    : { bg: 'bg-purple-50',  color: 'text-purple-500', path: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
                register: { bg: 'bg-green-50',   color: 'text-green-500',  path: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
            };
            container.innerHTML = activities.map(a => {
                const ic = iconMap[a.type] || iconMap.register;
                return `
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl ${ic.bg} flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 ${ic.color}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${ic.path}"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold text-gray-800">${a.user_name}</span>
                            ${a.aksi}
                            ${a.target ? `<span class="font-semibold text-blue-600">${a.target}</span>` : ''}
                        </p>
                    </div>
                    <p class="text-xs text-gray-400 shrink-0">${a.waktu_format}</p>
                </div>`;
            }).join('');
        }

        // ── User Terbaru — polling hanya update status text via Alpine ──
        // Tidak rebuild DOM agar Alpine state (isActive, showModal) tidak reset
        function updateRecentUsers(users) {
            // Skip rebuild — Alpine sudah handle state toggle real-time.
            // Polling ini cukup untuk stats & aktivitas.
            // Jika ingin sync status dari server, bisa tambahkan logika per-row di sini.
        }

        // ── Live indicator ──
        function setLive(online) {
            const dot = document.getElementById('live-dot');
            const txt = document.getElementById('live-text');
            if (!dot || !txt) return;
            if (online) {
                dot.className = 'w-2 h-2 rounded-full bg-green-400 animate-pulse';
                txt.textContent = 'Live';
                txt.className = 'text-xs font-semibold text-gray-500';
            } else {
                dot.className = 'w-2 h-2 rounded-full bg-red-400';
                txt.textContent = 'Offline';
                txt.className = 'text-xs font-semibold text-red-500';
            }
        }

        // ── Fetch data ──
        async function fetchData() {
            try {
                const res = await fetch(ENDPOINT, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF
                    }
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const json = await res.json();

                updateStats(json.stats);
                updateActivities(json.activities);
                updateRecentUsers(json.recentUsers);
                updateChart(json.chartData.labels, json.chartData.data);

                const tsEl = document.getElementById('last-updated');
                if (tsEl) tsEl.textContent = '· ' + json.timestamp;

                setLive(true);
            } catch (e) {
                console.warn('Realtime error:', e);
                setLive(false);
            }
        }

        // ── Boot ──
        waitForChart(() => {
            initChart(
                @json($grafikLabels),
                @json($grafikData)
            );
            fetchData();
            setInterval(fetchData, POLL_MS);
        });

    })();
    </script>

</x-app-layout>
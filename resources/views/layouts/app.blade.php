<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="transition-colors duration-300">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smart Learning Companion') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800,900&display=swap" rel="stylesheet"/>

    <!-- Flowbite -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#eef2f7]" x-data="{ sidebarOpen: true }">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="fixed top-0 left-0 z-40 h-screen w-64 bg-white border-r border-gray-100 flex flex-col"
               x-show="sidebarOpen"
               x-transition>

            <!-- Logo -->
            <div class="px-6 py-5 border-b border-gray-100">
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center gap-3">

                    <svg width="48" height="48" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="bookGrad" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#2563EB"/>
                                <stop offset="1" stop-color="#7C3AED"/>
                            </linearGradient>
                            <linearGradient id="pageGrad" x1="32" y1="14" x2="32" y2="50" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#EEF2FF"/>
                                <stop offset="1" stop-color="#C7D2FE"/>
                            </linearGradient>
                        </defs>

                        <!-- Background -->
                        <rect x="4" y="4" width="56" height="56" rx="16" fill="url(#bookGrad)"/>

                        <!-- Book left page -->
                        <path d="M14 18C14 16.9 14.9 16 16 16H30V48H16C14.9 48 14 47.1 14 46V18Z" fill="url(#pageGrad)" opacity="0.9"/>

                        <!-- Book right page -->
                        <path d="M34 16H48C49.1 16 50 16.9 50 18V46C50 47.1 49.1 48 48 48H34V16Z" fill="white" opacity="0.95"/>

                        <!-- Spine -->
                        <rect x="30" y="16" width="4" height="32" fill="url(#bookGrad)" opacity="0.4"/>

                        <!-- Lines left page -->
                        <line x1="18" y1="24" x2="27" y2="24" stroke="#818CF8" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="18" y1="29" x2="27" y2="29" stroke="#818CF8" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="18" y1="34" x2="24" y2="34" stroke="#818CF8" stroke-width="1.5" stroke-linecap="round"/>

                        <!-- AI spark right page -->
                        <circle cx="42" cy="32" r="2" fill="#6366F1"/>
                        <line x1="42" y1="24" x2="42" y2="28" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                        <line x1="42" y1="36" x2="42" y2="40" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                        <line x1="34" y1="32" x2="38" y2="32" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                        <line x1="46" y1="32" x2="50" y2="32" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                        <line x1="37" y1="27" x2="39.5" y2="29.5" stroke="#A5B4FC" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="44.5" y1="29.5" x2="47" y2="27" stroke="#A5B4FC" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="37" y1="37" x2="39.5" y2="34.5" stroke="#A5B4FC" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="44.5" y1="34.5" x2="47" y2="37" stroke="#A5B4FC" stroke-width="1.5" stroke-linecap="round"/>

                        <!-- Dot accents -->
                        <circle cx="16" cy="44" r="1.5" fill="#818CF8" opacity="0.5"/>
                        <circle cx="20" cy="44" r="1.5" fill="#818CF8" opacity="0.5"/>
                        <circle cx="24" cy="44" r="1.5" fill="#818CF8" opacity="0.5"/>
                    </svg>

                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent leading-none">SLC</h1>
                        <p class="text-xs text-gray-400 mt-1">Smart Learning Companion</p>
                    </div>
                </a>
            </div>

            <!-- Menu -->
            <nav class="flex-1 px-4 py-5 space-y-2 overflow-y-auto">

                @php
                    $dashRoute = auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard');
                    $dashActive = auth()->user()->role === 'admin' ? request()->routeIs('admin.dashboard') : request()->routeIs('dashboard');
                @endphp

                <a href="{{ $dashRoute }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-semibold transition-all duration-150 hover:-translate-y-0.5 hover:scale-[1.02] active:scale-95 active:translate-y-1 {{ $dashActive ? 'bg-blue-600 text-white shadow-xl shadow-blue-200' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('materi.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-semibold transition-all duration-150 hover:-translate-y-0.5 hover:scale-[1.02] active:scale-95 active:translate-y-1 {{ request()->routeIs('materi.*') ? 'bg-blue-600 text-white shadow-xl shadow-blue-200' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Materi Saya</span>
                </a>

                <a href="{{ route('ringkasan.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-semibold transition-all duration-150 hover:-translate-y-0.5 hover:scale-[1.02] active:scale-95 active:translate-y-1 {{ request()->routeIs('ringkasan.*') ? 'bg-blue-600 text-white shadow-xl shadow-blue-200' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Ringkasan AI</span>
                </a>

                <a href="{{ route('kuis.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-semibold transition-all duration-150 hover:-translate-y-0.5 hover:scale-[1.02] active:scale-95 active:translate-y-1 {{ request()->routeIs('kuis.*') ? 'bg-blue-600 text-white shadow-xl shadow-blue-200' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span>Kuis</span>
                </a>

                <a href="{{ route('riwayat.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-semibold transition-all duration-150 hover:-translate-y-0.5 hover:scale-[1.02] active:scale-95 active:translate-y-1 {{ request()->routeIs('riwayat.*') ? 'bg-blue-600 text-white shadow-xl shadow-blue-200' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0"/>
                    </svg>
                    <span>Riwayat</span>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-semibold transition-all duration-150 hover:-translate-y-0.5 hover:scale-[1.02] active:scale-95 active:translate-y-1 {{ request()->routeIs('profile.*') ? 'bg-blue-600 text-white shadow-xl shadow-blue-200' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Profil</span>
                </a>

                <a href="{{ route('pengaturan.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-semibold transition-all duration-150 hover:-translate-y-0.5 hover:scale-[1.02] active:scale-95 active:translate-y-1 {{ request()->routeIs('pengaturan.*') ? 'bg-blue-600 text-white shadow-xl shadow-blue-200' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Pengaturan</span>
                </a>

            </nav>

            <!-- Logout -->
            <div class="p-4 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-red-500 font-semibold transition-all duration-150 hover:bg-red-50 hover:-translate-y-0.5 hover:scale-[1.02] active:scale-95 active:translate-y-1">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>

        </aside>

        <!-- Right Content -->
        <div class="flex-1 flex flex-col transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-0'">

            <!-- Topbar -->
            <header class="sticky top-0 z-30 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-500 hover:text-gray-700 transition-all duration-150 hover:-translate-y-0.5 active:scale-95">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Avatar + Dropdown -->
                <div class="flex items-center gap-3" x-data="{ dropdownOpen: false }">
                    <div class="text-right">
                        <h2 class="text-sm font-bold text-gray-800">{{ auth()->user()->name }}</h2>
                        <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                    </div>

                    <div class="relative">
                        <button @click="dropdownOpen = !dropdownOpen"
                                @keydown.escape.window="dropdownOpen = false"
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 text-white flex items-center justify-center font-bold text-sm shadow-md hover:opacity-90 transition-opacity focus:outline-none">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="dropdownOpen"
                             @click.outside="dropdownOpen = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                             class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50"
                             style="display:none;">

                            <div class="px-4 py-3 border-b border-gray-100 mb-1">
                                <p class="text-[13px] font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>

                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Profil
                            </a>

                            <a href="{{ route('pengaturan.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Pengaturan
                            </a>

                            <div class="border-t border-gray-100 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-6">

                @if(session('success'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="flex items-center justify-between gap-3 mb-5 bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-5 py-4 rounded-2xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                    <button @click="show = false" class="text-green-400 hover:text-green-600 transition">›</button>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="flex items-center justify-between gap-3 mb-5 bg-red-50 border border-red-200 text-red-700 text-sm font-medium px-5 py-4 rounded-2xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                    <button @click="show = false" class="text-red-400 hover:text-red-600 transition">›</button>
                </div>
                @endif

                @if(session('warning'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="flex items-center justify-between gap-3 mb-5 bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm font-medium px-5 py-4 rounded-2xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        {{ session('warning') }}
                    </div>
                    <button @click="show = false" class="text-yellow-400 hover:text-yellow-600 transition">›</button>
                </div>
                @endif

                @if(session('info'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="flex items-center justify-between gap-3 mb-5 bg-blue-50 border border-blue-200 text-blue-700 text-sm font-medium px-5 py-4 rounded-2xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('info') }}
                    </div>
                    <button @click="show = false" class="text-blue-400 hover:text-blue-600 transition">›</button>
                </div>
                @endif

                {{ $slot }}

            </main>

        </div>

    </div>

    <!-- Alpine -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Flowbite -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</body>

</html>
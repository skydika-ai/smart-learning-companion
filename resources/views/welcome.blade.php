<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Smart Learning Companion — Platform belajar cerdas berbasis AI untuk mahasiswa Indonesia">
    <title>SLC — Smart Learning Companion</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --blue: #2563EB;
            --indigo: #4F46E5;
            --violet: #7C3AED;
            --navy: #0F172A;
            --text: #1E293B;
            --muted: #64748B;
            --border: #E2E8F0;
            --surface: #F8FAFC;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .shimmer-text {
            background: linear-gradient(90deg, #93C5FD 0%, #818CF8 25%, #C4B5FD 50%, #818CF8 75%, #93C5FD 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 4s linear infinite;
        }
        @keyframes shimmer { to { background-position: 200% center; } }

        .dot-grid-dark {
            background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 28px 28px;
        }

        @keyframes fadeUp    { from { opacity:0; transform:translateY(32px); } to { opacity:1; transform:translateY(0); } }
        @keyframes float     { 0%,100% { transform:translateY(0px); } 50% { transform:translateY(-12px); } }
        @keyframes floatB    { 0%,100% { transform:translateY(0px) rotate(-2deg); } 50% { transform:translateY(-8px) rotate(2deg); } }
        @keyframes spinSlow  { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
        @keyframes ping2     { 0%,100% { transform:scale(1); opacity:1; } 50% { transform:scale(1.15); opacity:.6; } }
        @keyframes gradShift { 0%,100% { background-position:0% 50%; } 50% { background-position:100% 50%; } }
        @keyframes marquee   { from { transform:translateX(0); } to { transform:translateX(-50%); } }
        @keyframes scrollBob { 0%,100% { transform:translateY(0); } 50% { transform:translateY(6px); } }
        @keyframes blobFloat { 0%,100% { border-radius:60% 40% 30% 70%/60% 30% 70% 40%; transform:translate(0,0) scale(1); }
                               33%  { border-radius:30% 60% 70% 40%/50% 60% 30% 60%; transform:translate(20px,-15px) scale(1.05); }
                               66%  { border-radius:50% 60% 30% 40%/40% 50% 60% 50%; transform:translate(-10px,10px) scale(0.97); } }

        .animate-fadeUp   { animation: fadeUp .7s ease both; }
        .animate-float    { animation: float 5s ease-in-out infinite; }
        .animate-floatB   { animation: floatB 6s ease-in-out infinite; }
        .animate-spinSlow { animation: spinSlow 18s linear infinite; }
        .animate-ping2    { animation: ping2 2s ease-in-out infinite; }
        .animate-gradShift{ animation: gradShift 8s ease infinite; background-size:200% 200%; }
        .animate-marquee  { animation: marquee 28s linear infinite; }
        .animate-scrollBob{ animation: scrollBob 2s ease-in-out infinite; }
        .animate-blobFloat{ animation: blobFloat 10s ease-in-out infinite; }

        .reveal, .reveal-left, .reveal-right, .reveal-scale {
            opacity: 0;
            transition: opacity .7s ease, transform .7s ease;
        }
        .reveal       { transform: translateY(40px); }
        .reveal-left  { transform: translateX(-40px); }
        .reveal-right { transform: translateX(40px); }
        .reveal-scale { transform: scale(.92); }
        .revealed     { opacity: 1 !important; transform: none !important; }

        .btn-primary {
            display: inline-flex; align-items: center; gap: .5rem;
            background: linear-gradient(135deg, var(--blue), var(--indigo));
            color: white; font-weight: 700; border-radius: .75rem;
            padding: .75rem 1.75rem; font-size: .95rem;
            box-shadow: 0 4px 20px rgba(79,70,229,.4);
            transition: all .25s ease;
        }
        .btn-primary:hover { transform: translateY(-2px) scale(1.02); box-shadow: 0 8px 28px rgba(79,70,229,.5); }

        .btn-ghost {
            display: inline-flex; align-items: center; gap: .5rem;
            border: 1.5px solid rgba(255,255,255,.2); color: rgba(255,255,255,.85);
            font-weight: 600; border-radius: .75rem; padding: .75rem 1.75rem; font-size: .95rem;
            background: rgba(255,255,255,.05); backdrop-filter: blur(8px);
            transition: all .25s ease;
        }
        .btn-ghost:hover { background: rgba(255,255,255,.12); border-color: rgba(255,255,255,.4); transform: translateY(-2px); }

        .btn-white {
            display: inline-flex; align-items: center; gap: .5rem;
            background: white; color: var(--indigo);
            font-weight: 700; border-radius: .75rem; padding: .75rem 1.75rem; font-size: .95rem;
            box-shadow: 0 4px 20px rgba(0,0,0,.15);
            transition: all .25s ease;
        }
        .btn-white:hover { transform: translateY(-2px) scale(1.02); box-shadow: 0 8px 28px rgba(0,0,0,.2); }

        .nav-blur {
            position: sticky; top: 0; z-index: 50;
            background: rgba(15,23,42,.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,.06);
            transition: box-shadow .3s;
        }
        .nav-blur.scrolled { box-shadow: 0 4px 24px rgba(0,0,0,.4); }

        .hero-card {
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.12);
            backdrop-filter: blur(20px);
            border-radius: 1.25rem;
            padding: 1.5rem;
        }

        .feature-card {
            background: white; border: 1px solid var(--border);
            border-radius: 1.25rem; padding: 2rem;
            transition: all .3s ease; position: relative; overflow: hidden;
        }
        .feature-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(79,70,229,.12); border-color: #C7D2FE; }
        .feature-card::before {
            content:''; position:absolute; inset:0; border-radius:1.25rem;
            background: linear-gradient(135deg, rgba(37,99,235,.04), rgba(124,58,237,.04));
            opacity:0; transition: opacity .3s;
        }
        .feature-card:hover::before { opacity:1; }

        .testi-card {
            background: white; border: 1px solid var(--border); border-radius: 1.25rem;
            padding: 1.75rem; transition: all .3s ease;
        }
        .testi-card:hover { transform: translateY(-4px); box-shadow: 0 16px 32px rgba(0,0,0,.08); }

        .stats-section {
            background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 50%, #0F172A 100%);
            background-size: 200% 200%;
            animation: gradShift 10s ease infinite;
        }

        .cta-section {
            background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 60%, #2E1065 100%);
            position: relative; overflow: hidden;
        }
        .cta-section::before {
            content:''; position:absolute; top:-40%; left:-20%;
            width:600px; height:600px; border-radius:50%;
            background: radial-gradient(circle, rgba(79,70,229,.18) 0%, transparent 70%);
            pointer-events:none;
        }
        .cta-section::after {
            content:''; position:absolute; bottom:-40%; right:-20%;
            width:500px; height:500px; border-radius:50%;
            background: radial-gradient(circle, rgba(124,58,237,.15) 0%, transparent 70%);
            pointer-events:none;
        }

        .marquee-track { display:flex; gap:1rem; white-space:nowrap; }

        .scroll-indicator {
            width: 28px; height: 44px; border: 2px solid rgba(255,255,255,.3);
            border-radius: 14px; display:flex; justify-content:center; padding-top:6px;
        }
        .scroll-dot {
            width: 4px; height: 8px; border-radius:2px;
            background: rgba(255,255,255,.6);
            animation: scrollBob 2s ease-in-out infinite;
        }
    </style>
</head>

<body class="antialiased bg-white text-slate-800 overflow-x-hidden">

<!-- ══ NAVBAR ══ -->
<nav class="nav-blur" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <svg width="40" height="40" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="bookGrad" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#2563EB"/><stop offset="1" stop-color="#7C3AED"/>
                        </linearGradient>
                        <linearGradient id="pageGrad" x1="32" y1="14" x2="32" y2="50" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#EEF2FF"/><stop offset="1" stop-color="#C7D2FE"/>
                        </linearGradient>
                    </defs>
                    <rect x="4" y="4" width="56" height="56" rx="16" fill="url(#bookGrad)"/>
                    <path d="M14 18C14 16.9 14.9 16 16 16H30V48H16C14.9 48 14 47.1 14 46V18Z" fill="url(#pageGrad)" opacity="0.9"/>
                    <path d="M34 16H48C49.1 16 50 16.9 50 18V46C50 47.1 49.1 48 48 48H34V16Z" fill="white" opacity="0.95"/>
                    <rect x="30" y="16" width="4" height="32" fill="url(#bookGrad)" opacity="0.4"/>
                    <line x1="18" y1="24" x2="27" y2="24" stroke="#818CF8" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="18" y1="29" x2="27" y2="29" stroke="#818CF8" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="18" y1="34" x2="24" y2="34" stroke="#818CF8" stroke-width="1.5" stroke-linecap="round"/>
                    <circle cx="42" cy="32" r="2" fill="#6366F1"/>
                    <line x1="42" y1="24" x2="42" y2="28" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                    <line x1="42" y1="36" x2="42" y2="40" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                    <line x1="34" y1="32" x2="38" y2="32" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                    <line x1="46" y1="32" x2="50" y2="32" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                    <line x1="37" y1="27" x2="39.5" y2="29.5" stroke="#A5B4FC" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="44.5" y1="29.5" x2="47" y2="27" stroke="#A5B4FC" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="37" y1="37" x2="39.5" y2="34.5" stroke="#A5B4FC" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="44.5" y1="34.5" x2="47" y2="37" stroke="#A5B4FC" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <div>
                    <span class="text-white font-800 text-lg leading-none tracking-tight">SLC</span>
                    <span class="block text-xs text-slate-400 font-400 leading-none">Smart Learning Companion</span>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-7">
                @foreach([['#fitur','Fitur'],['#cara-kerja','Cara Kerja'],['#tim','Tim'],['#faq','FAQ']] as $nav)
                <a href="{{ $nav[0] }}" class="text-slate-400 hover:text-white text-sm font-500 transition-colors duration-200">{{ $nav[1] }}</a>
                @endforeach
            </div>

            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-slate-300 hover:text-white text-sm font-600 px-4 py-2 rounded-lg hover:bg-white/10 transition-all">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary !py-2 !px-5 !text-sm">
                    Mulai Gratis
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>

            <button id="mobile-menu-btn" class="md:hidden text-slate-400 hover:text-white p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        <div id="mobile-menu" class="md:hidden hidden pb-4 pt-2 border-t border-white/10 mt-2">
            <div class="flex flex-col gap-1">
                @foreach([['#fitur','Fitur'],['#cara-kerja','Cara Kerja'],['#tim','Tim'],['#faq','FAQ']] as $nav)
                <a href="{{ $nav[0] }}" class="text-slate-400 hover:text-white text-sm font-500 px-3 py-2 rounded-lg hover:bg-white/10 transition-all">{{ $nav[1] }}</a>
                @endforeach
                <div class="flex gap-2 mt-3 px-1">
                    <a href="{{ route('login') }}" class="flex-1 text-center text-slate-300 text-sm font-600 px-4 py-2 rounded-lg border border-white/20 hover:bg-white/10 transition-all">Masuk</a>
                    <a href="{{ route('register') }}" class="flex-1 text-center btn-primary !py-2 !text-sm justify-center">Mulai Gratis</a>
                </div>
            </div>
        </div>
    </div>
</nav>


<!-- ══ HERO ══ -->
<section class="relative min-h-screen dot-grid-dark overflow-hidden flex items-center" style="background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 50%, #0F172A 100%);">

    <div class="absolute top-1/4 left-1/4 w-96 h-96 opacity-20 pointer-events-none" style="background: radial-gradient(circle, #4F46E5, transparent 70%); border-radius:50%; animation: blobFloat 12s ease-in-out infinite;"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 opacity-15 pointer-events-none" style="background: radial-gradient(circle, #7C3AED, transparent 70%); border-radius:50%; animation: blobFloat 15s ease-in-out infinite reverse;"></div>

    <div class="absolute top-20 right-24 w-64 h-64 opacity-10 pointer-events-none">
        <div class="absolute inset-0 rounded-full border-2 border-dashed border-indigo-400 animate-spinSlow"></div>
        <div class="absolute inset-6 rounded-full border border-dashed border-violet-400" style="animation: spinSlow 25s linear infinite reverse;"></div>
        <div class="absolute inset-12 rounded-full border border-dotted border-blue-400" style="animation: spinSlow 18s linear infinite;"></div>
    </div>
    <div class="absolute bottom-32 left-16 w-40 h-40 opacity-10 pointer-events-none">
        <div class="absolute inset-0 rounded-full border-2 border-dashed border-blue-400 animate-spinSlow"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 w-full">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

            <div>
                <div class="animate-fadeUp inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-1.5 mb-8 border border-white/15">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-400"></span>
                    </span>
                    <span class="text-blue-200 text-xs font-600 tracking-wide">Project Capstone — Universitas Tadulako</span>
                </div>

                <h1 class="animate-fadeUp text-4xl sm:text-5xl xl:text-6xl font-900 leading-tight text-white mb-6" style="animation-delay:.1s">
                    Belajar Lebih Cerdas<br>
                    dengan <span class="shimmer-text">Kecerdasan Buatan</span>
                </h1>

                <p class="animate-fadeUp text-slate-400 text-lg font-400 leading-relaxed mb-10 max-w-lg" style="animation-delay:.2s">
                    Upload materi kuliah, dapatkan ringkasan instan, kuis otomatis, dan pantau progres belajarmu — semua dalam satu platform AI yang dirancang untuk mahasiswa.
                </p>

                <div class="animate-fadeUp flex flex-col sm:flex-row gap-4 mb-12" style="animation-delay:.3s">
                    <a href="{{ route('register') }}" class="btn-primary text-base !px-7 !py-3.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Coba Sekarang
                    </a>
                    <a href="#cara-kerja" class="btn-ghost text-base !px-7 !py-3.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Lihat Demo
                    </a>
                </div>

                {{-- Tech stack badges --}}
                <div class="animate-fadeUp flex flex-wrap items-center gap-2" style="animation-delay:.4s">
                    @foreach(['Laravel 13','PHP 8.3','Tailwind CSS','Alpine.js','AI-Powered','MySQL'] as $tech)
                    <span class="inline-flex items-center gap-1.5 bg-white/08 border border-white/12 text-slate-300 text-xs font-600 px-3 py-1.5 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 opacity-70"></span>
                        {{ $tech }}
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- Hero card --}}
            <div class="relative flex justify-center lg:justify-end">
                <div class="hero-card animate-float w-full max-w-sm relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-violet-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                            </div>
                            <span class="text-white text-sm font-700">AI Memproses...</span>
                        </div>
                        <div class="flex gap-1">
                            <span class="w-2 h-2 rounded-full bg-blue-400 animate-ping2"></span>
                            <span class="w-2 h-2 rounded-full bg-indigo-400 animate-ping2" style="animation-delay:.3s"></span>
                            <span class="w-2 h-2 rounded-full bg-violet-400 animate-ping2" style="animation-delay:.6s"></span>
                        </div>
                    </div>

                    <div class="bg-white/10 rounded-xl p-3 mb-4 border border-white/10">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-8 h-10 rounded bg-blue-500/30 border border-blue-400/30 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-white text-xs font-600">Materi_Algoritma_ch3.pdf</p>
                                <p class="text-slate-400 text-xs">2.4 MB • 48 halaman</p>
                            </div>
                        </div>
                        @php
                        $tasks = [
                            ['Membaca dokumen','100%','from-blue-400 to-blue-600'],
                            ['Membuat ringkasan','78%','from-indigo-400 to-indigo-600'],
                            ['Membuat kuis','45%','from-violet-400 to-violet-600'],
                        ];
                        @endphp
                        @foreach($tasks as $task)
                        <div class="mb-2 last:mb-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-slate-300 text-xs">{{ $task[0] }}</span>
                                <span class="text-white text-xs font-600">{{ $task[1] }}</span>
                            </div>
                            <div class="h-1.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r {{ $task[2] }} rounded-full" style="width:{{ $task[1] }};"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        @foreach([['12','Poin Kunci'],['5','Kuis Dibuat'],['93%','Akurasi']] as $stat)
                        <div class="bg-white/08 rounded-lg p-2 text-center border border-white/10">
                            <p class="text-white font-800 text-base">{{ $stat[0] }}</p>
                            <p class="text-slate-400 text-xs leading-tight">{{ $stat[1] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Floating chips --}}
                <div class="absolute -top-5 -left-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-xs font-700 px-3 py-2 rounded-xl shadow-lg animate-floatB z-20 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    PDF Diproses
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white/15 backdrop-blur text-white text-xs font-600 px-3 py-2 rounded-xl border border-white/20 animate-floatB z-20 flex items-center gap-1.5" style="animation-delay:1s">
                    <span class="text-green-400">●</span> Ringkasan Siap
                </div>
                <div class="absolute top-1/3 -right-6 bg-gradient-to-r from-violet-500 to-purple-600 text-white text-xs font-700 px-3 py-2 rounded-xl shadow-lg animate-floatB z-20 flex items-center gap-1.5" style="animation-delay:1.5s">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Kuis Dibuat
                </div>
                <div class="absolute bottom-1/3 -right-3 bg-white/15 backdrop-blur text-white text-xs font-600 px-3 py-2 rounded-xl border border-white/20 animate-float z-20" style="animation-delay:2s">
                    🎯 Skor: 95/100
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2">
            <span class="text-slate-500 text-xs">Scroll untuk menjelajahi</span>
            <div class="scroll-indicator"><div class="scroll-dot"></div></div>
        </div>
    </div>
</section>


<!-- ══ MARQUEE ══ -->
<div class="bg-slate-900 border-y border-slate-800 py-4 overflow-hidden">
    <div class="flex">
        @php
        $tags = ['📄 PDF','📝 DOCX','🤖 AI Ringkasan','⚡ Kuis Otomatis','📊 Pantau Progres','🎯 Poin Kunci','💡 Penjelasan AI','🔍 Analisis Mendalam','📈 Statistik Belajar','💬 Tanya AI','📚 Multi-Format','🌐 Akses 24/7','✨ Instant Result','🔔 Notifikasi','⚙️ Laravel 13'];
        @endphp
        @for($m=0;$m<2;$m++)
        <div class="marquee-track animate-marquee flex-none" @if($m===1) aria-hidden="true" @endif>
            @foreach(array_merge($tags,$tags) as $tag)
            <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-slate-800 text-slate-300 text-sm font-500 border border-slate-700 whitespace-nowrap">{{ $tag }}</span>
            @endforeach
        </div>
        @endfor
    </div>
</div>


<!-- ══ STATS (teknologi, bukan klaim palsu) ══ -->
<section class="stats-section py-20 relative overflow-hidden">
    <div class="absolute inset-0 dot-grid-dark opacity-40 pointer-events-none"></div>
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-indigo-500 to-transparent opacity-50"></div>
    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-violet-500 to-transparent opacity-50"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 reveal">
            <p class="text-indigo-400 text-sm font-700 tracking-widest uppercase mb-3">Dibangun dengan Teknologi Modern</p>
            <h2 class="text-3xl sm:text-4xl font-800 text-white">Stack yang Solid & Terpercaya</h2>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            @php
            $stats = [
                ['4','Fitur Utama','⚡','from-blue-500 to-indigo-600'],
                ['3+','Format File','📄','from-indigo-500 to-violet-600'],
                ['100%','AI-Powered','🤖','from-violet-500 to-purple-600'],
                ['24/7','Akses Kapan Saja','🌐','from-purple-500 to-pink-600'],
            ];
            @endphp
            @foreach($stats as $i => $s)
            <div class="text-center reveal" style="transition-delay: {{ $i * 0.1 }}s">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br {{ $s[3] }} text-2xl mb-4 shadow-lg">{{ $s[2] }}</div>
                <div class="text-4xl sm:text-5xl font-900 text-white mb-2">{{ $s[0] }}</div>
                <div class="text-slate-400 font-500 text-sm">{{ $s[1] }}</div>
            </div>
            @endforeach
        </div>

        {{-- Tech stack pills --}}
        <div class="mt-14 flex flex-wrap justify-center gap-3 reveal">
            @php
            $techs = [
                ['Laravel 13','bg-red-500/20 text-red-300 border-red-500/30'],
                ['PHP 8.3','bg-indigo-500/20 text-indigo-300 border-indigo-500/30'],
                ['MySQL','bg-blue-500/20 text-blue-300 border-blue-500/30'],
                ['Tailwind CSS','bg-cyan-500/20 text-cyan-300 border-cyan-500/30'],
                ['Alpine.js','bg-teal-500/20 text-teal-300 border-teal-500/30'],
                ['Flowbite 2.3','bg-violet-500/20 text-violet-300 border-violet-500/30'],
                ['Laravel Breeze','bg-pink-500/20 text-pink-300 border-pink-500/30'],
                ['Guzzle HTTP','bg-orange-500/20 text-orange-300 border-orange-500/30'],
            ];
            @endphp
            @foreach($techs as $tech)
            <span class="px-4 py-2 rounded-full border text-sm font-600 {{ $tech[1] }}">{{ $tech[0] }}</span>
            @endforeach
        </div>
    </div>
</section>


<!-- ══ FITUR ══ -->
<section id="fitur" class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="inline-block bg-blue-50 text-blue-600 text-xs font-700 tracking-widest uppercase px-4 py-1.5 rounded-full border border-blue-100 mb-4">Fitur Unggulan</span>
            <h2 class="text-3xl sm:text-4xl font-800 text-slate-900 mb-4">Semua yang Kamu Butuhkan<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-violet-600">dalam Satu Platform</span></h2>
            <p class="text-slate-500 text-lg max-w-xl mx-auto font-400">Dari upload materi hingga evaluasi — SLC mengotomatiskan proses belajarmu dengan AI.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
            $features = [
                ['M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12','Upload Mudah','Dukung format PDF, DOCX, dan TXT. Upload sekali, belajar berkali-kali tanpa ribet.','from-blue-500 to-blue-600','bg-blue-50','Multi Format'],
                ['M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z','Ringkasan AI','Poin kunci dan ringkasan cerdas dari materi panjang dihasilkan otomatis oleh AI.','from-indigo-500 to-indigo-600','bg-indigo-50','Instan'],
                ['M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z','Kuis Otomatis','AI membuat soal latihan yang relevan langsung dari materi yang kamu upload.','from-violet-500 to-violet-600','bg-violet-50','Auto Generate'],
                ['M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z','Pantau Progres','Dashboard untuk melacak progres belajar, skor kuis, dan aktivitas harianmu.','from-pink-500 to-rose-500','bg-pink-50','Real-time'],
            ];
            @endphp
            @foreach($features as $i => $f)
            <div class="feature-card reveal" style="transition-delay: {{ $i * 0.1 }}s">
                <div class="{{ $f[4] }} inline-flex p-3 rounded-xl mb-5">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br {{ $f[3] }} flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f[0] }}"/></svg>
                    </div>
                </div>
                <span class="inline-block text-xs font-700 text-slate-400 tracking-widest uppercase mb-2">{{ $f[5] }}</span>
                <h3 class="text-slate-900 font-800 text-lg mb-2">{{ $f[1] }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed font-400">{{ $f[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- ══ CARA KERJA ══ -->
<section id="cara-kerja" class="py-24 relative overflow-hidden" style="background: linear-gradient(160deg, #F8FAFC 0%, #EEF2FF 50%, #F8FAFC 100%);">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="inline-block bg-indigo-50 text-indigo-600 text-xs font-700 tracking-widest uppercase px-4 py-1.5 rounded-full border border-indigo-100 mb-4">Sangat Mudah</span>
            <h2 class="text-3xl sm:text-4xl font-800 text-slate-900 mb-4">Mulai Belajar dalam<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600">3 Langkah Sederhana</span></h2>
            <p class="text-slate-500 text-lg max-w-md mx-auto font-400">Tidak perlu konfigurasi rumit. Upload, tunggu sebentar, mulai belajar.</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8 relative z-10">
            @php
            $steps = [
                ['M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12','Upload Materi','Unggah file PDF, DOCX, atau format lainnya dari perangkatmu. Sistem akan langsung memproses.','from-blue-500 to-blue-600','Step 1'],
                ['M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z','AI Menganalisis','AI membaca, memahami, dan mengekstrak poin-poin penting dari materimu secara otomatis.','from-indigo-500 to-violet-600','Step 2'],
                ['M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z','Belajar & Berlatih','Dapatkan ringkasan, kerjakan kuis otomatis, dan pantau progres belajarmu.','from-violet-500 to-purple-600','Step 3'],
            ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="text-center reveal" style="transition-delay: {{ $i * 0.15 }}s">
                <div class="relative inline-flex">
                    <div class="w-28 h-28 rounded-3xl bg-gradient-to-br {{ $step[3] }} flex items-center justify-center mb-6 shadow-xl mx-auto">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step[0] }}"/></svg>
                    </div>
                    <span class="absolute -top-2 -right-2 w-7 h-7 rounded-full bg-white text-slate-800 text-xs font-900 flex items-center justify-center shadow-md border-2 border-slate-100">{{ $i+1 }}</span>
                </div>
                <span class="inline-block text-xs font-700 tracking-widest uppercase text-indigo-500 mb-2">{{ $step[4] }}</span>
                <h3 class="text-slate-900 font-800 text-xl mb-3">{{ $step[1] }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed font-400 max-w-xs mx-auto">{{ $step[2] }}</p>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-12 reveal">
            <a href="{{ route('register') }}" class="btn-primary !px-8 !py-3.5">
                Coba Sekarang — Gratis!
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>


<!-- ══ TIM PENGEMBANG ══ -->
<section id="tim" class="py-24 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="inline-block bg-violet-50 text-violet-600 text-xs font-700 tracking-widest uppercase px-4 py-1.5 rounded-full border border-violet-100 mb-4">Kelompok 6</span>
            <h2 class="text-3xl sm:text-4xl font-800 text-slate-900 mb-4">Tim di Balik SLC</h2>
            <p class="text-slate-500 text-lg max-w-md mx-auto font-400">Dibangun oleh 4 mahasiswa Universitas Tadulako yang bersemangat di bidang teknologi dan pendidikan.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
            $team = [
                ['Fidya Rahayu','F55124100','UI/UX & Frontend','Merancang tampilan dan pengalaman pengguna yang intuitif menggunakan Blade, Tailwind, dan Flowbite.','M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z','from-blue-500 to-indigo-600','bg-blue-50 text-blue-700',['Blade','Tailwind','Flowbite']],
                ['Muh. Dimas Syahputra','F55124120','AI Service & API','Mengintegrasikan layanan AI dan membangun komunikasi API menggunakan Guzzle HTTP client.','M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z','from-indigo-500 to-violet-600','bg-indigo-50 text-indigo-700',['AI','Guzzle','API']],
                ['Andika','F55124083','Database & Model','Merancang struktur database, migration, model Eloquent, dan seeder untuk data awal aplikasi.','M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4','from-violet-500 to-purple-600','bg-violet-50 text-violet-700',['MySQL','Migration','Seeder']],
                ['Moh. Haikal Butudoka','F55124106','Backend & Controller','Membangun logika bisnis, controller, middleware, dan routing di backend Laravel.','M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4','from-pink-500 to-rose-500','bg-pink-50 text-pink-700',['Laravel','PHP','Middleware']],
            ];
            @endphp

            @foreach($team as $i => $member)
            <div class="testi-card reveal text-center group" style="transition-delay: {{ $i * 0.1 }}s">
                <div class="relative inline-flex mb-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $member[5] }} flex items-center justify-center mx-auto shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $member[4] }}"/>
                        </svg>
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-white border-2 border-slate-100 flex items-center justify-center">
                        <span class="w-2 h-2 rounded-full bg-green-400"></span>
                    </span>
                </div>
                <p class="text-slate-800 font-700 text-sm mb-0.5">{{ $member[0] }}</p>
                <p class="text-slate-400 text-xs mb-1">{{ $member[1] }}</p>
                <p class="text-xs font-700 text-transparent bg-clip-text bg-gradient-to-r {{ $member[5] }} mb-3">{{ $member[2] }}</p>
                <p class="text-slate-500 text-xs leading-relaxed font-400 mb-4">{{ $member[3] }}</p>
                <div class="flex flex-wrap justify-center gap-1.5">
                    @foreach($member[7] as $tag)
                    <span class="text-xs font-600 px-2.5 py-1 rounded-full {{ $member[6] }} bg-opacity-60">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-12 reveal">
            <div class="inline-flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-2xl px-6 py-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </div>
                <div class="text-left">
                    <p class="text-slate-800 font-700 text-sm">Universitas Tadulako</p>
                    <p class="text-slate-400 text-xs">Kelompok 6 — Pemrograman Web / Capstone Project</p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ══ FAQ ══ -->
<section id="faq" class="py-24 bg-slate-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 reveal">
            <span class="inline-block bg-blue-50 text-blue-600 text-xs font-700 tracking-widest uppercase px-4 py-1.5 rounded-full border border-blue-100 mb-4">FAQ</span>
            <h2 class="text-3xl sm:text-4xl font-800 text-slate-900 mb-4">Pertanyaan yang Sering Ditanyakan</h2>
            <p class="text-slate-500 text-base font-400">Tidak menemukan jawaban yang kamu cari? <a href="{{ route('register') }}" class="text-blue-600 font-600 hover:underline">Hubungi kami</a>.</p>
        </div>

        <div class="space-y-3 reveal" x-data="{ open: null }">
            @php
            $faqs = [
                ['Apakah SLC bisa digunakan secara gratis?','Ya! SLC menyediakan akun gratis dengan fitur dasar yang sudah lengkap — upload materi, ringkasan AI, dan kuis otomatis. Cukup daftar dengan email dan langsung bisa digunakan.'],
                ['Format file apa saja yang didukung SLC?','SLC mendukung format PDF, DOCX, dan TXT. Kami terus mengembangkan dukungan format baru berdasarkan kebutuhan pengguna.'],
                ['Bagaimana cara kerja ringkasan AI di SLC?','Setelah kamu mengupload materi, AI akan membaca dan mengekstrak poin-poin kunci secara otomatis. Hasilnya berupa ringkasan terstruktur yang mudah dipelajari.'],
                ['Apakah data materi saya aman?','Keamanan data adalah prioritas kami. Semua file dienkripsi saat penyimpanan dan pengiriman. Kamu juga bisa menghapus seluruh datamu kapan saja dari dashboard.'],
                ['Apakah SLC bisa diakses dari smartphone?','Ya! SLC sepenuhnya responsif dan bisa diakses dari browser di HP, tablet, maupun laptop tanpa perlu install aplikasi tambahan.'],
            ];
            @endphp

            @foreach($faqs as $i => $faq)
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden transition-all duration-200"
                 :class="{ 'border-blue-200 shadow-sm': open === {{ $i }} }">
                <button
                    class="w-full text-left px-6 py-4 flex items-center justify-between gap-4 hover:bg-slate-50 transition-colors"
                    @click="open = open === {{ $i }} ? null : {{ $i }}"
                >
                    <span class="text-slate-800 font-600 text-sm sm:text-base">{{ $faq[0] }}</span>
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-300"
                         :class="{ 'rotate-180 text-blue-500': open === {{ $i }} }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === {{ $i }}" x-collapse class="px-6 pb-5">
                    <p class="text-slate-500 text-sm leading-relaxed font-400">{{ $faq[1] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- ══ CTA ══ -->
<section class="cta-section py-28">
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="reveal">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-1.5 mb-8 border border-white/15">
                <span class="text-2xl">🚀</span>
                <span class="text-blue-200 text-xs font-600 tracking-wide">Project Capstone — Kelompok 6 Universitas Tadulako</span>
            </div>
            <h2 class="text-4xl sm:text-5xl font-900 text-white mb-6 leading-tight">
                Siap Belajar Lebih<br><span class="shimmer-text">Cerdas & Efisien?</span>
            </h2>
            <p class="text-slate-400 text-lg font-400 mb-10 max-w-xl mx-auto">
                Daftar sekarang dan eksplorasi fitur AI yang kami bangun. Gratis, tanpa perlu kartu kredit.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="btn-white !py-3.5 !px-8 !text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="btn-ghost !py-3.5 !px-8 !text-base">
                    Sudah punya akun? Masuk
                </a>
            </div>
            <p class="text-slate-500 text-sm mt-6 font-400">✓ Gratis &nbsp;·&nbsp; ✓ Tanpa kartu kredit &nbsp;·&nbsp; ✓ Setup dalam 30 detik</p>
        </div>
    </div>
</section>


<!-- ══ FOOTER ══ -->
<footer class="bg-slate-950 border-t border-slate-800">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid md:grid-cols-3 gap-12 mb-12">

            <div>
                <a href="{{ url('/') }}" class="flex items-center gap-3 mb-5">
                    <svg width="36" height="36" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="bG2" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#2563EB"/><stop offset="1" stop-color="#7C3AED"/>
                            </linearGradient>
                            <linearGradient id="pG2" x1="32" y1="14" x2="32" y2="50" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#EEF2FF"/><stop offset="1" stop-color="#C7D2FE"/>
                            </linearGradient>
                        </defs>
                        <rect x="4" y="4" width="56" height="56" rx="16" fill="url(#bG2)"/>
                        <path d="M14 18C14 16.9 14.9 16 16 16H30V48H16C14.9 48 14 47.1 14 46V18Z" fill="url(#pG2)" opacity="0.9"/>
                        <path d="M34 16H48C49.1 16 50 16.9 50 18V46C50 47.1 49.1 48 48 48H34V16Z" fill="white" opacity="0.95"/>
                        <rect x="30" y="16" width="4" height="32" fill="url(#bG2)" opacity="0.4"/>
                        <circle cx="42" cy="32" r="2" fill="#6366F1"/>
                        <line x1="42" y1="24" x2="42" y2="28" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                        <line x1="42" y1="36" x2="42" y2="40" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                        <line x1="34" y1="32" x2="38" y2="32" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                        <line x1="46" y1="32" x2="50" y2="32" stroke="#6366F1" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <div>
                        <span class="text-white font-800 text-lg leading-none">SLC</span>
                        <span class="block text-xs text-slate-500 leading-none">Smart Learning Companion</span>
                    </div>
                </a>
                <p class="text-slate-400 text-sm leading-relaxed font-400 mb-5 max-w-xs">
                    Platform belajar berbasis AI yang dibangun sebagai Capstone Project oleh Kelompok 6, Universitas Tadulako.
                </p>
                <div class="flex items-center gap-2 text-slate-500 text-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-ping2"></span>
                    Semua sistem berjalan normal
                </div>
            </div>

            <div>
                <h4 class="text-white font-700 text-sm uppercase tracking-widest mb-5">Navigasi</h4>
                <ul class="space-y-3">
                    @foreach([['#fitur','Fitur Platform'],['#cara-kerja','Cara Kerja'],['#tim','Tim Pengembang'],['#faq','FAQ'],['route:register','Daftar'],['route:login','Masuk']] as $link)
                    @php $href = str_starts_with($link[0], 'route:') ? route(substr($link[0],6)) : $link[0]; @endphp
                    <li>
                        <a href="{{ $href }}" class="text-slate-400 hover:text-white text-sm font-500 transition-colors flex items-center gap-2 group">
                            <svg class="w-3 h-3 text-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            {{ $link[1] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-white font-700 text-sm uppercase tracking-widest mb-5">Tim Pengembang</h4>
                <p class="text-slate-500 text-xs font-500 mb-4 uppercase tracking-wider">Universitas Tadulako — Kelompok 6</p>
                <ul class="space-y-3">
                    @php
                    $footerTeam = [
                        ['Fidya Rahayu','UI/UX & Frontend','from-blue-400 to-blue-600'],
                        ['Muh. Dimas Syahputra','AI Service','from-indigo-400 to-violet-600'],
                        ['Andika','Database & Model','from-violet-400 to-purple-600'],
                        ['Moh. Haikal Butudoka','Backend & API','from-pink-400 to-rose-600'],
                    ];
                    @endphp
                    @foreach($footerTeam as $m)
                    <li class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br {{ $m[2] }} flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-xs font-800">{{ substr($m[0],0,1) }}</span>
                        </div>
                        <div>
                            <p class="text-slate-300 text-xs font-600">{{ $m[0] }}</p>
                            <p class="text-slate-500 text-xs">{{ $m[1] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-800 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-slate-500 text-sm font-400">
                © {{ date('Y') }} <span class="text-slate-400 font-600">SLC — Smart Learning Companion</span>. Kelompok 6.
            </p>
            <p class="text-slate-600 text-xs">Universitas Tadulako · Capstone Project</p>
        </div>
    </div>
</footer>


<!-- ══ Scripts ══ -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Navbar shadow on scroll
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 30);
    });

    // Mobile menu
    document.getElementById('mobile-menu-btn').addEventListener('click', () => {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });

    // Scroll reveal
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });

    document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale').forEach(el => {
        revealObserver.observe(el);
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

});
</script>
</body>
</html>
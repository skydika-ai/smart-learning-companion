<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SLC') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800,900&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet"/>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

        body {
            background: linear-gradient(160deg, #EEF2FF 0%, #F0F9FF 35%, #FAFBFF 65%, #F5F3FF 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .dot-grid {
            background-image: radial-gradient(circle, #C7D2FE 1px, transparent 1px);
            background-size: 32px 32px;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }
        @keyframes spinSlow { to { transform: rotate(360deg); } }
        @keyframes ping2 {
            0%   { transform: scale(1); opacity: .5; }
            100% { transform: scale(1.9); opacity: 0; }
        }

        .shimmer-text {
            background: linear-gradient(100deg, #1D4ED8 0%, #6366F1 30%, #A78BFA 55%, #818CF8 70%, #2563EB 100%);
            background-size: 250% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 5s linear infinite;
        }

        .auth-card {
            background: rgba(255,255,255,0.92);
            border: 1px solid rgba(226,232,240,0.9);
            border-radius: 32px;
            box-shadow: 0 32px 80px rgba(15,23,42,0.10), 0 8px 24px rgba(15,23,42,0.06);
            backdrop-filter: blur(24px);
            animation: fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) forwards;
        }

        .ring-spin {
            border: 1px dashed rgba(165,180,252,0.4);
            border-radius: 50%;
            position: absolute;
            pointer-events: none;
        }

        .btn-primary {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #2563EB 0%, #4F46E5 100%);
            box-shadow: 0 6px 20px rgba(37,99,235,0.35), 0 2px 6px rgba(37,99,235,0.18);
            transition: all 0.25s cubic-bezier(0.34,1.56,0.64,1);
            color: #fff;
            font-weight: 700;
            font-size: 14.5px;
            border-radius: 16px;
            padding: 14px 28px;
            border: none;
            cursor: pointer;
            text-align: center;
            letter-spacing: 0.01em;
        }
        .btn-primary:hover {
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 12px 30px rgba(37,99,235,0.42), 0 4px 10px rgba(37,99,235,0.22);
        }
        .btn-primary:active { transform: scale(0.97); }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #CBD5E1;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #F1F5F9;
        }
    </style>
</head>

<body class="antialiased">

    <!-- Dot grid -->
    <div class="fixed inset-0 dot-grid opacity-30 pointer-events-none z-0"></div>

    <!-- Blobs -->
    <div class="fixed top-0 left-0 w-[500px] h-[500px] bg-blue-200/20 rounded-full blur-[90px] pointer-events-none z-0"></div>
    <div class="fixed bottom-0 right-0 w-[450px] h-[450px] bg-violet-200/20 rounded-full blur-[80px] pointer-events-none z-0"></div>

    <div class="relative z-10 min-h-screen flex flex-col items-center justify-center px-4 py-12">

        <!-- Logo -->
        <a href="/" class="flex items-center gap-3 mb-8" style="animation:fadeUp 0.5s ease forwards;">

            <svg width="52" height="52" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
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

                <!-- Background pill -->
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
                <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent leading-none">
                    SLC
                </h1>
                <p class="text-xs text-slate-400 font-medium">Smart Learning Companion</p>
            </div>

        </a>

        <!-- Card wrapper + spinning rings -->
        <div class="w-full max-w-[440px] relative">
            <div class="ring-spin w-[530px] h-[530px] -left-[45px] -top-[45px]" style="animation:spinSlow 30s linear infinite;"></div>
            <div class="ring-spin w-[420px] h-[420px] left-[10px] top-[10px]" style="animation:spinSlow 22s linear infinite reverse;"></div>

            <div class="auth-card px-8 py-9 relative z-10">
                {{ $slot }}
            </div>
        </div>

        <p class="mt-8 text-xs text-slate-400 font-medium" style="animation:fadeUp 0.9s 0.3s ease forwards; opacity:0;">
            © 2026 Smart Learning Companion · Kelompok 6
        </p>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') — PB Garles DSS</title>
    <meta name="description" content="Login ke Decision Support System Evaluasi Pertandingan Bulutangkis">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .login-bg {
            background: linear-gradient(135deg, rgba(12, 18, 34, 0.65) 0%, rgba(15, 23, 42, 0.75) 50%, rgba(10, 15, 30, 0.85) 100%),
                        url('{{ asset("images/bg-login.jpg") }}?v={{ time() }}') center/cover no-repeat fixed;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
        }

        /* Badminton court lines pattern */
        .login-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                /* Court center line */
                linear-gradient(90deg, transparent 49.5%, rgba(255,255,255,0.04) 49.5%, rgba(255,255,255,0.04) 50.5%, transparent 50.5%),
                /* Court horizontal lines */
                linear-gradient(0deg, transparent 24%, rgba(255,255,255,0.03) 24%, rgba(255,255,255,0.03) 24.5%, transparent 24.5%),
                linear-gradient(0deg, transparent 49%, rgba(255,255,255,0.03) 49%, rgba(255,255,255,0.03) 49.5%, transparent 49.5%),
                linear-gradient(0deg, transparent 74%, rgba(255,255,255,0.03) 74%, rgba(255,255,255,0.03) 74.5%, transparent 74.5%),
                /* Court boundary */
                linear-gradient(90deg, transparent 10%, rgba(255,255,255,0.025) 10%, rgba(255,255,255,0.025) 10.3%, transparent 10.3%),
                linear-gradient(90deg, transparent 89.7%, rgba(255,255,255,0.025) 89.7%, rgba(255,255,255,0.025) 90%, transparent 90%);
            z-index: 0;
        }

        /* Glowing accent orbs */
        .login-bg::after {
            content: '';
            position: absolute;
            top: -15%;
            right: -10%;
            width: 50%;
            height: 70%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
        }

        .orb-bottom {
            position: absolute;
            bottom: -15%;
            left: -8%;
            width: 45%;
            height: 60%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 65%);
            border-radius: 50%;
            z-index: 0;
        }

        .orb-center {
            position: absolute;
            top: 40%;
            left: 55%;
            width: 30%;
            height: 40%;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.08) 0%, transparent 60%);
            border-radius: 50%;
            z-index: 0;
        }

        /* Floating shuttlecock decorations */
        .shuttlecock-float {
            position: absolute;
            opacity: 0.06;
            z-index: 0;
            animation: floatSlow 8s ease-in-out infinite;
        }
        .shuttlecock-float:nth-child(2) {
            animation-delay: -3s;
            animation-duration: 10s;
        }
        .shuttlecock-float:nth-child(3) {
            animation-delay: -5s;
            animation-duration: 12s;
        }

        @keyframes floatSlow {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(5deg); }
            66% { transform: translateY(10px) rotate(-3deg); }
        }

        /* Login card glassmorphism */
        .login-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            animation: fadeSlideUp 0.6s ease-out;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Net pattern divider */
        .net-pattern {
            height: 3px;
            background: repeating-linear-gradient(
                90deg,
                rgba(99, 102, 241, 0.4) 0px,
                rgba(99, 102, 241, 0.4) 4px,
                transparent 4px,
                transparent 8px
            );
            border-radius: 2px;
        }
    </style>
</head>
<body class="login-bg antialiased">
    {{-- Decorative orbs --}}
    <div class="orb-bottom"></div>
    <div class="orb-center"></div>

    {{-- Floating shuttlecock SVGs --}}
    <svg class="shuttlecock-float" style="top: 10%; left: 8%; width: 120px;" viewBox="0 0 64 64" fill="white">
        <ellipse cx="32" cy="12" rx="6" ry="8"/>
        <path d="M26 18 C26 18 20 40 20 50 C20 56 44 56 44 50 C44 40 38 18 38 18" fill="white"/>
        <line x1="26" y1="28" x2="38" y2="28" stroke="white" stroke-width="0.5" opacity="0.5"/>
        <line x1="24" y1="36" x2="40" y2="36" stroke="white" stroke-width="0.5" opacity="0.5"/>
        <line x1="22" y1="44" x2="42" y2="44" stroke="white" stroke-width="0.5" opacity="0.5"/>
    </svg>

    <svg class="shuttlecock-float" style="bottom: 15%; right: 12%; width: 90px; transform: rotate(30deg);" viewBox="0 0 64 64" fill="white">
        <ellipse cx="32" cy="12" rx="6" ry="8"/>
        <path d="M26 18 C26 18 20 40 20 50 C20 56 44 56 44 50 C44 40 38 18 38 18" fill="white"/>
    </svg>

    <svg class="shuttlecock-float" style="top: 60%; left: 75%; width: 70px; transform: rotate(-20deg);" viewBox="0 0 64 64" fill="white">
        <ellipse cx="32" cy="12" rx="6" ry="8"/>
        <path d="M26 18 C26 18 20 40 20 50 C20 56 44 56 44 50 C44 40 38 18 38 18" fill="white"/>
    </svg>

    {{-- Main content --}}
    <div class="min-h-screen flex items-center justify-center p-4 relative z-10">
        @yield('content')
    </div>
</body>
</html>

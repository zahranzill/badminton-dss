<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Evaluasi Pertandingan Bulutangkis Sektor Ganda — Badminton DSS</title>
    <meta name="description" content="Decision Support System (DSS) Berbasis Web untuk Evaluasi Pertandingan Bulutangkis Sektor Ganda menggunakan Analisis Data Statistik & Rule-Based Logic.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-glow {
            position: relative;
        }
        .hero-glow::before {
            content: '';
            position: absolute;
            top: -20%;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 140%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, transparent 60%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body class="bg-slate-50 antialiased text-slate-600">
    <!-- Navbar -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-slate-800 font-bold text-base leading-tight">Badminton DSS</h1>
                    <p class="text-slate-400 text-xs font-medium">Evaluasi Sektor Ganda</p>
                </div>
            </a>
            <div>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary text-sm shadow-md">
                        Dashboard Pelatih
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary text-sm shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Masuk Sistem
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-glow py-16 sm:py-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                    Decision Support System Berbasis Web
                </span>
                <h2 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Evaluasi Taktis & Performa <br>
                    <span class="gradient-text">Ganda Bulutangkis secara Objektif</span>
                </h2>
                <p class="mt-6 text-lg text-slate-500 leading-relaxed">
                    Sistem Pendukung Keputusan berbasis aturan (Rule-Based DSS) untuk membantu pelatih dan analis menganalisis statistik pertandingan ganda secara presisi berdasarkan riwayat rally.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary px-8 py-3 rounded-xl shadow-lg shadow-indigo-500/20 text-base justify-center">
                            Ke Dashboard Utama
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary px-8 py-3 rounded-xl shadow-lg shadow-indigo-500/20 text-base justify-center">
                            Mulai Evaluasi Sekarang
                        </a>
                    @endauth
                    <a href="#fitur" class="btn btn-outline px-8 py-3 rounded-xl text-base justify-center">
                        Pelajari Fitur
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Utama -->
    <section id="fitur" class="py-16 bg-white border-t border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h3 class="text-3xl font-bold text-slate-900">Mengapa Memilih Badminton DSS?</h3>
                <p class="mt-4 text-slate-500">Fitur lengkap dirancang khusus untuk memetakan kekuatan dan kelemahan taktis pasangan ganda bulutangkis secara sistematis.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="p-6 rounded-2xl border border-slate-100 hover:shadow-lg transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 mb-3">Rule-Based DSS Evaluation</h4>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Sistem mencocokkan data statistik pertandingan dengan database aturan (knowledge base) untuk merumuskan kesimpulan objektif dan fokus perbaikan taktis ganda.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="p-6 rounded-2xl border border-slate-100 hover:shadow-lg transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center text-violet-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 mb-3">Statistik Performa Komprehensif</h4>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Melacak tingkat kesalahan (error rate), tipe kesalahan paling dominan (netting, out, miskomunikasi), performa pada poin kritis, serta rata-rata durasi dan pukulan rally.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="p-6 rounded-2xl border border-slate-100 hover:shadow-lg transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 mb-3">Verifikasi & Finalisasi Data</h4>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Perekaman data rally secara detail. Fitur verifikasi memastikan data pertandingan telah lengkap dan valid sebelum diolah menjadi statistik akhir.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left flex flex-col sm:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-white font-bold text-sm">Badminton DSS</span>
                    <p class="text-slate-500 text-xs">Aplikasi Evaluasi Pertandingan Sektor Ganda</p>
                </div>
            </div>
            <p class="text-xs text-slate-500">
                &copy; {{ date('Y') }} Badminton DSS. Hak Cipta Dilindungi Undang-Undang.
            </p>
        </div>
    </footer>
</body>
</html>

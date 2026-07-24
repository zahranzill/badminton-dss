{{-- Sidebar Navigation --}}
<aside id="sidebar" class="sidebar fixed top-0 left-0 w-[260px] h-screen flex flex-col z-40">
    {{-- Logo Header --}}
    <div class="px-5 py-5 border-b border-slate-700/40 relative z-10">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-full overflow-hidden shadow-lg flex-shrink-0 ring-2 ring-emerald-400/50 shadow-emerald-500/20 group-hover:scale-105 transition-transform duration-200">
                <img src="{{ asset('images/logo-garles.png') }}?v={{ time() }}" alt="PB Garles" class="w-full h-full object-cover">
            </div>
            <div>
                <h1 class="text-white font-extrabold text-base leading-tight tracking-wide flex items-center gap-1.5">
                    PB Garles
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                </h1>
                <span class="inline-block mt-0.5 px-2 py-0.2 rounded-full text-[10px] bg-emerald-500/15 text-emerald-300 font-semibold tracking-wider border border-emerald-500/20">
                    DSS Evaluasi Ganda
                </span>
            </div>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto custom-scrollbar relative z-10">
        {{-- Menu Utama --}}
        <p class="px-3 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
            <span class="w-1 h-1 rounded-full bg-emerald-400"></span>
            Menu Utama
        </p>

        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Profil Akun</span>
        </a>

        {{-- Data Master --}}
        <p class="px-3 mt-6 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
            <span class="w-1 h-1 rounded-full bg-cyan-400"></span>
            Data Master
        </p>

        <a href="{{ route('players.index') }}" class="sidebar-link {{ request()->routeIs('players.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Data Pemain</span>
        </a>

        <a href="{{ route('pairs.index') }}" class="sidebar-link {{ request()->routeIs('pairs.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span>Data Pasangan</span>
        </a>

        {{-- Pertandingan --}}
        <p class="px-3 mt-6 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
            <span class="w-1 h-1 rounded-full bg-indigo-400"></span>
            Pertandingan
        </p>

        <a href="{{ route('matches.index') }}" class="sidebar-link {{ request()->routeIs('matches.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <span>Data Pertandingan</span>
        </a>

        <a href="{{ route('verification.index') }}" class="sidebar-link {{ request()->routeIs('verification.*') ? 'active' : '' }} flex items-center justify-between w-full">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Verifikasi Data</span>
            </div>
            @if(isset($noRallyCount) && $noRallyCount > 0)
                <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-rose-800 bg-rose-200 rounded-full animate-pulse">
                    {{ $noRallyCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('statistics.index') }}" class="sidebar-link {{ request()->routeIs('statistics.*') ? 'active' : '' }} flex items-center justify-between w-full">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Statistik Performa</span>
            </div>
            @if(isset($unevaluatedCount) && $unevaluatedCount > 0)
                <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-amber-800 bg-amber-200 rounded-full animate-pulse">
                    {{ $unevaluatedCount }}
                </span>
            @endif
        </a>

        {{-- Evaluasi --}}
        <p class="px-3 mt-6 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
            <span class="w-1 h-1 rounded-full bg-amber-400"></span>
            Evaluasi DSS
        </p>

        <a href="{{ route('evaluation-rules.index') }}" class="sidebar-link {{ request()->routeIs('evaluation-rules.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <span>Aturan Evaluasi</span>
        </a>

        <a href="{{ route('evaluation-history.index') }}" class="sidebar-link {{ request()->routeIs('evaluation-history.*') || request()->routeIs('evaluations.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span>Riwayat Evaluasi</span>
        </a>

        <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            <span>Laporan</span>
        </a>
    </nav>

    {{-- Logout --}}
    <div class="px-3 py-4 border-t border-slate-700/50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link w-full text-rose-400 hover:text-rose-300 hover:bg-rose-500/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>

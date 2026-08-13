{{-- Top Navbar --}}
<header class="sticky top-0 z-20 bg-white/90 backdrop-blur-lg border-b border-slate-200/80 shadow-xs">
    <div class="flex items-center justify-between px-3 sm:px-4 lg:px-6 h-16 max-w-full flex-nowrap">
        {{-- Left: Hamburger + Page Title --}}
        <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1 mr-2">
            <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors flex-shrink-0" aria-label="Menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="min-w-0 flex-1">
                <h2 class="text-base sm:text-lg font-bold text-slate-800 truncate leading-tight">@yield('page-title', 'Dashboard')</h2>
                @hasSection('page-subtitle')
                    <p class="text-xs text-slate-500 truncate hidden sm:block">@yield('page-subtitle')</p>
                @endif
            </div>
        </div>

        {{-- Right: User Info --}}
        <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
            <div class="text-right hidden md:block">
                <p class="text-sm font-semibold text-slate-700 leading-tight">{{ Auth::user()->name }}</p>
                <p class="text-[11px] text-slate-500 font-medium">Pelatih / Analis</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-500 to-amber-500 flex items-center justify-center text-white font-bold text-sm shadow-md ring-2 ring-emerald-500/20 hover:scale-105 transition-transform flex-shrink-0" title="Profil {{ Auth::user()->name }}">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </a>
        </div>
    </div>
</header>

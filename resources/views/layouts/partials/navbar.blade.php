{{-- Top Navbar --}}
<header class="sticky top-0 z-20 bg-white/80 backdrop-blur-lg border-b border-slate-200/60">
    <div class="flex items-center justify-between px-4 lg:px-6 h-16">
        {{-- Left: Hamburger + Page Title --}}
        <div class="flex items-center gap-3">
            <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div>
                <h2 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h2>
                @hasSection('page-subtitle')
                    <p class="text-xs text-slate-500">@yield('page-subtitle')</p>
                @endif
            </div>
        </div>

        {{-- Right: User Info --}}
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-medium text-slate-700">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-500">Pelatih / Analis</p>
            </div>
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-semibold text-sm shadow-md">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </div>
    </div>
</header>

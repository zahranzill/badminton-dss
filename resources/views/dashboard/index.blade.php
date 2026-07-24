@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan informasi dan statistik evaluasi pertandingan')

@section('content')

{{-- Hero Welcome Banner --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-900 to-emerald-900 p-6 sm:p-8 mb-6 shadow-xl">
    {{-- Decorative elements --}}
    <div class="absolute top-0 right-0 w-64 h-64 opacity-10">
        <svg viewBox="0 0 64 64" fill="white" class="w-full h-full">
            <ellipse cx="32" cy="10" rx="7" ry="9"/>
            <path d="M25 16 C25 16 18 38 18 50 C18 58 46 58 46 50 C46 38 39 16 39 16 Z"/>
        </svg>
    </div>
    <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl"></div>
    <div class="absolute top-4 right-1/3 w-20 h-20 bg-indigo-400/10 rounded-full blur-xl"></div>

    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-emerald-300 text-sm font-medium mb-1">👋 Selamat Datang Kembali,</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-white">{{ Auth::user()->name }}</h2>
            <p class="text-slate-300 text-sm mt-2 max-w-lg">
                Pantau performa pertandingan ganda, analisis evaluasi DSS, dan tingkatkan kualitas permainan tim Garles.
            </p>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('matches.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-white text-sm font-semibold transition-all shadow-lg shadow-emerald-500/25 hover:shadow-emerald-400/30 hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Pertandingan Baru
            </a>
        </div>
    </div>

    {{-- Quick stats in hero --}}
    <div class="relative z-10 mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/10">
            <p class="text-xs text-slate-300 font-medium">Win Rate</p>
            <p class="text-2xl font-bold text-white mt-0.5">{{ $winRate }}%</p>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/10">
            <p class="text-xs text-slate-300 font-medium">Total Laga</p>
            <p class="text-2xl font-bold text-white mt-0.5">{{ $totalMatches }}</p>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/10">
            <p class="text-xs text-slate-300 font-medium">Menang</p>
            <p class="text-2xl font-bold text-emerald-300 mt-0.5">{{ $matchStats['wins'] }}</p>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/10">
            <p class="text-xs text-slate-300 font-medium">Kalah</p>
            <p class="text-2xl font-bold text-rose-300 mt-0.5">{{ $matchStats['losses'] }}</p>
        </div>
    </div>
</div>

{{-- Stat Cards Row --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Total Pemain --}}
    <div class="group relative overflow-hidden rounded-xl bg-white p-5 border border-slate-200 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
        <div class="absolute top-0 right-0 w-20 h-20 bg-indigo-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Pemain</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $totalPlayers }}</p>
                <p class="text-xs text-slate-400 mt-1">Terdaftar</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Pasangan --}}
    <div class="group relative overflow-hidden rounded-xl bg-white p-5 border border-slate-200 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
        <div class="absolute top-0 right-0 w-20 h-20 bg-violet-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Pasangan</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $totalPairs }}</p>
                <p class="text-xs text-slate-400 mt-1">Formasi Ganda</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center shadow-lg shadow-violet-500/25">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Pertandingan --}}
    <div class="group relative overflow-hidden rounded-xl bg-white p-5 border border-slate-200 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
        <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Pertandingan</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $totalMatches }}</p>
                <p class="text-xs text-slate-400 mt-1">Laga Tercatat</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/25">
                <svg class="w-6 h-6 text-white" viewBox="0 0 64 64" fill="currentColor">
                    <ellipse cx="32" cy="10" rx="6" ry="8"/>
                    <path d="M26 16 C26 16 20 35 20 45 C20 52 44 52 44 45 C44 35 38 16 38 16 Z" opacity="0.8"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Total Evaluasi --}}
    <div class="group relative overflow-hidden rounded-xl bg-white p-5 border border-slate-200 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
        <div class="absolute top-0 right-0 w-20 h-20 bg-amber-50 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Evaluasi</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $totalEvaluations }}</p>
                <p class="text-xs text-slate-400 mt-1">Analisis DSS</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/25">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- Unevaluated Alert --}}
@if($unevaluatedCount > 0)
<div class="rounded-xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 p-4 mb-6 flex items-center gap-4">
    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
    </div>
    <div class="flex-1">
        <p class="text-sm font-semibold text-amber-800">{{ $unevaluatedCount }} pertandingan belum dievaluasi</p>
        <p class="text-xs text-amber-600 mt-0.5">Data sudah difinalkan dan siap untuk dianalisis oleh modul DSS.</p>
    </div>
    <a href="{{ route('statistics.index') }}" class="text-xs font-semibold text-amber-700 hover:text-amber-800 bg-amber-100 hover:bg-amber-200 px-3 py-1.5 rounded-lg transition-colors flex-shrink-0">
        Jalankan Evaluasi →
    </a>
</div>
@endif

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Win Rate Gauge --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-slate-800">Win Rate</h3>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $winRate >= 50 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                {{ $winRate >= 50 ? '↑ Baik' : '↓ Perlu Perbaikan' }}
            </span>
        </div>
        <div class="flex items-center justify-center" style="height: 200px;">
            <canvas id="winRateChart"></canvas>
        </div>
        <div class="text-center mt-2">
            <p class="text-xs text-slate-500">
                <span class="font-semibold text-emerald-600">{{ $matchStats['wins'] }} menang</span> dari
                <span class="font-semibold text-slate-700">{{ $totalMatches }} pertandingan</span>
            </p>
        </div>
    </div>

    {{-- Monthly Performance Bar Chart --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-slate-800">Performa Bulanan</h3>
            <span class="text-xs text-slate-400">6 bulan terakhir</span>
        </div>
        <div class="flex items-center justify-center" style="height: 220px;">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    {{-- Error Distribution --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-slate-800">Distribusi Jenis Error</h3>
            <span class="text-xs text-slate-400">Semua pertandingan</span>
        </div>
        <div class="flex items-center justify-center" style="height: 220px;">
            <canvas id="errorChart"></canvas>
        </div>
    </div>
</div>

{{-- Status Pipeline + Quick Actions --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Match Status Pipeline --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-slate-800 mb-4">Status Data Pertandingan</h3>
        <div class="grid grid-cols-3 gap-3">
            <div class="relative rounded-xl bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 p-4 text-center">
                <div class="w-10 h-10 rounded-full bg-amber-200 mx-auto mb-2 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <p class="text-2xl font-bold text-amber-800">{{ $matchStats['draft'] }}</p>
                <p class="text-xs font-medium text-amber-600 mt-1">Draft</p>
                <p class="text-[10px] text-amber-500 mt-0.5">Belum difinalisasi</p>
            </div>
            <div class="relative rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 p-4 text-center">
                <div class="w-10 h-10 rounded-full bg-blue-200 mx-auto mb-2 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <p class="text-2xl font-bold text-blue-800">{{ $matchStats['final'] }}</p>
                <p class="text-xs font-medium text-blue-600 mt-1">Final</p>
                <p class="text-[10px] text-blue-500 mt-0.5">Siap dievaluasi</p>
            </div>
            <div class="relative rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200 p-4 text-center">
                <div class="w-10 h-10 rounded-full bg-emerald-200 mx-auto mb-2 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <p class="text-2xl font-bold text-emerald-800">{{ $matchStats['evaluated'] }}</p>
                <p class="text-xs font-medium text-emerald-600 mt-1">Dievaluasi</p>
                <p class="text-[10px] text-emerald-500 mt-0.5">Analisis selesai</p>
            </div>
        </div>
        {{-- Progress bar --}}
        @php
            $total = max($matchStats['draft'] + $matchStats['final'] + $matchStats['evaluated'], 1);
            $draftPct = round(($matchStats['draft'] / $total) * 100);
            $finalPct = round(($matchStats['final'] / $total) * 100);
            $evalPct = 100 - $draftPct - $finalPct;
        @endphp
        <div class="mt-4 h-2 rounded-full bg-slate-100 overflow-hidden flex">
            <div class="bg-amber-400 transition-all" style="width: {{ $draftPct }}%"></div>
            <div class="bg-blue-400 transition-all" style="width: {{ $finalPct }}%"></div>
            <div class="bg-emerald-400 transition-all" style="width: {{ $evalPct }}%"></div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <h3 class="text-sm font-semibold text-slate-800 mb-4">Aksi Cepat</h3>
        <div class="space-y-2.5">
            <a href="{{ route('matches.create') }}" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-indigo-50 border border-slate-100 hover:border-indigo-200 transition-all group">
                <div class="w-9 h-9 rounded-lg bg-indigo-100 group-hover:bg-indigo-200 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-700 group-hover:text-indigo-700">Tambah Pertandingan</p>
                    <p class="text-[10px] text-slate-400">Input data laga baru</p>
                </div>
            </a>
            <a href="{{ route('verification.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-100 hover:border-blue-200 transition-all group">
                <div class="w-9 h-9 rounded-lg bg-blue-100 group-hover:bg-blue-200 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-700 group-hover:text-blue-700">Verifikasi Data</p>
                    <p class="text-[10px] text-slate-400">Finalisasi pertandingan</p>
                </div>
            </a>
            <a href="{{ route('statistics.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-200 transition-all group">
                <div class="w-9 h-9 rounded-lg bg-emerald-100 group-hover:bg-emerald-200 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-700 group-hover:text-emerald-700">Statistik & Evaluasi</p>
                    <p class="text-[10px] text-slate-400">Analisis performa DSS</p>
                </div>
            </a>
            <a href="{{ route('reports.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-amber-50 border border-slate-100 hover:border-amber-200 transition-all group">
                <div class="w-9 h-9 rounded-lg bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-700 group-hover:text-amber-700">Cetak Laporan</p>
                    <p class="text-[10px] text-slate-400">Generate PDF / Print</p>
                </div>
            </a>
        </div>
    </div>
</div>

{{-- Recent Matches & Evaluations --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Matches Timeline --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" viewBox="0 0 64 64" fill="currentColor"><ellipse cx="32" cy="10" rx="5" ry="7"/><path d="M27 15 C27 15 22 32 22 40 C22 46 42 46 42 40 C42 32 37 15 37 15 Z" opacity="0.7"/></svg>
                    Pertandingan Terbaru
                </h3>
                <a href="{{ route('matches.index') }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium">Lihat Semua →</a>
            </div>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($recentMatches as $match)
                <div class="px-5 py-3.5 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                    {{-- Result indicator --}}
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                        {{ $match->result === 'Menang' ? 'bg-emerald-100' : 'bg-rose-100' }}">
                        @if($match->result === 'Menang')
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-700 truncate">{{ $match->pair->name ?? '-' }} vs {{ $match->opponent_name }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-slate-400">{{ $match->match_date->format('d M Y') }}</span>
                            <span class="text-xs text-slate-300">•</span>
                            <span class="text-xs font-medium text-slate-500">{{ $match->final_score }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="badge {{ $match->result === 'Menang' ? 'badge-win' : 'badge-lose' }}">
                            {{ $match->result }}
                        </span>
                        <span class="badge {{ $match->status === 'Draft' ? 'badge-draft' : ($match->status === 'Final' ? 'badge-final' : 'badge-evaluated') }}">
                            {{ $match->status }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="px-5 py-10 text-center">
                    <svg class="w-10 h-10 text-slate-200 mx-auto mb-2" viewBox="0 0 64 64" fill="currentColor"><ellipse cx="32" cy="10" rx="7" ry="9"/><path d="M25 16 C25 16 18 38 18 50 C18 58 46 58 46 50 C46 38 39 16 39 16 Z" opacity="0.4"/></svg>
                    <p class="text-sm text-slate-400">Belum ada data pertandingan.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Evaluations --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Evaluasi Terbaru
                </h3>
                <a href="{{ route('evaluation-history.index') }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium">Lihat Semua →</a>
            </div>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($recentEvaluations as $eval)
                <div class="px-5 py-3.5 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center justify-between mb-1.5">
                        <p class="text-sm font-semibold text-slate-700">
                            {{ $eval->matchGame->pair->name ?? '-' }} vs {{ $eval->matchGame->opponent_name ?? '-' }}
                        </p>
                        <a href="{{ route('evaluations.show', $eval->match_game_id) }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium bg-primary-50 hover:bg-primary-100 px-2 py-0.5 rounded-lg transition-colors">
                            Lihat →
                        </a>
                    </div>
                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ Str::limit($eval->overall_evaluation, 120) }}</p>
                </div>
            @empty
                <div class="px-5 py-10 text-center">
                    <svg class="w-10 h-10 text-slate-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-sm text-slate-400">Belum ada hasil evaluasi.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    // ===== Win Rate Doughnut Gauge =====
    const winRate = {{ $winRate }};
    new Chart(document.getElementById('winRateChart'), {
        type: 'doughnut',
        data: {
            labels: ['Menang', 'Kalah'],
            datasets: [{
                data: [{{ $matchStats['wins'] }}, {{ $matchStats['losses'] }}],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.85)',
                    'rgba(244, 63, 94, 0.85)'
                ],
                borderWidth: 0,
                hoverOffset: 6,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 10,
                    titleFont: { family: 'Inter', size: 12 },
                    bodyFont: { family: 'Inter', size: 11 },
                    cornerRadius: 8
                }
            }
        },
        plugins: [{
            id: 'centerText',
            afterDraw: function(chart) {
                const { ctx, width, height } = chart;
                ctx.save();
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.font = 'bold 28px Inter';
                ctx.fillStyle = '#1e293b';
                ctx.fillText(winRate + '%', width / 2, height / 2 - 6);
                ctx.font = '500 11px Inter';
                ctx.fillStyle = '#94a3b8';
                ctx.fillText('Win Rate', width / 2, height / 2 + 16);
                ctx.restore();
            }
        }]
    });

    // ===== Monthly Performance Bar Chart =====
    const monthlyData = @json($monthlyMatches);
    const monthLabels = monthlyData.map(m => {
        const [y, mo] = m.month.split('-');
        const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        return months[parseInt(mo) - 1] + ' ' + y.slice(2);
    });

    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [
                {
                    label: 'Menang',
                    data: monthlyData.map(m => m.wins),
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderRadius: 6,
                    borderSkipped: false
                },
                {
                    label: 'Kalah',
                    data: monthlyData.map(m => m.losses),
                    backgroundColor: 'rgba(244, 63, 94, 0.8)',
                    borderRadius: 6,
                    borderSkipped: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15, usePointStyle: true, pointStyle: 'rectRounded', font: { size: 11, family: 'Inter' } }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 10,
                    cornerRadius: 8,
                    titleFont: { family: 'Inter', size: 12 },
                    bodyFont: { family: 'Inter', size: 11 }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10, family: 'Inter' } }
                },
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { size: 10, family: 'Inter' } },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                }
            }
        }
    });

    // ===== Error Distribution Polar Area =====
    const errorData = @json($errorDistribution);
    const errorColors = [
        'rgba(244, 63, 94, 0.75)',
        'rgba(249, 115, 22, 0.75)',
        'rgba(234, 179, 8, 0.75)',
        'rgba(16, 185, 129, 0.75)',
        'rgba(59, 130, 246, 0.75)',
        'rgba(139, 92, 246, 0.75)',
        'rgba(236, 72, 153, 0.75)',
        'rgba(20, 184, 166, 0.75)'
    ];

    if (errorData.length > 0) {
        new Chart(document.getElementById('errorChart'), {
            type: 'polarArea',
            data: {
                labels: errorData.map(e => e.error_type),
                datasets: [{
                    data: errorData.map(e => e.total),
                    backgroundColor: errorColors.slice(0, errorData.length),
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 10, usePointStyle: true, font: { size: 10, family: 'Inter' } }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { family: 'Inter', size: 12 },
                        bodyFont: { family: 'Inter', size: 11 }
                    }
                },
                scales: {
                    r: {
                        ticks: { display: false },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    }
                }
            }
        });
    } else {
        document.getElementById('errorChart').parentElement.innerHTML = '<div class="flex flex-col items-center justify-center h-full text-slate-400"><svg class="w-10 h-10 mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg><p class="text-sm">Belum ada data error</p></div>';
    }
</script>
@endpush

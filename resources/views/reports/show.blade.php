@extends('layouts.app')

@section('title', 'Laporan Hasil Evaluasi')
@section('page-title', 'Laporan Hasil Evaluasi')
@section('page-subtitle')
    Tinjauan Laporan: {{ $match->pair->name ?? '-' }} vs {{ $match->opponent_name }}
@endsection

@section('content')
<div class="max-w-4xl space-y-6">
    {{-- Action Bar --}}
    <div class="flex items-center justify-between flex-wrap gap-3 no-print">
        <a href="{{ route('reports.index') }}" class="btn btn-outline text-xs">
            Kembali ke Daftar Laporan
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.print', $match->id) }}?action=pdf" target="_blank" class="btn text-xs bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 hover:border-rose-300 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Unduh PDF
            </a>
            <a href="{{ route('reports.print', $match->id) }}?action=print" target="_blank" class="btn btn-primary text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak
            </a>
        </div>
    </div>

    {{-- Report Paper Frame --}}
    <div class="card bg-white p-6 sm:p-8 space-y-6 shadow-md border border-slate-200">
        {{-- Header KOP Laporan --}}
        <div class="border-b-2 border-slate-800 pb-5 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 uppercase">LAPORAN EVALUASI PERTANDINGAN</h2>
                <p class="text-xs text-slate-500 font-semibold tracking-wide mt-1">DECISION SUPPORT SYSTEM (DSS) EVALUASI GANDA BULUTANGKIS</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-900 flex items-center justify-center text-white flex-shrink-0 font-extrabold text-lg shadow-inner">
                🏸
            </div>
        </div>

        {{-- Identitas Pertandingan --}}
        <div>
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 bg-slate-100 px-3 py-1 rounded">
                I. Identitas Pertandingan
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="space-y-2">
                    <p><span class="font-semibold text-slate-400 block sm:inline sm:w-32 sm:inline-block">Tanggal:</span> {{ $match->match_date->format('d F Y') }}</p>
                    <p><span class="font-semibold text-slate-400 block sm:inline sm:w-32 sm:inline-block">Kategori Ganda:</span> {{ $match->pair_category }}</p>
                    <p><span class="font-semibold text-slate-400 block sm:inline sm:w-32 sm:inline-block">Tipe Laga:</span> {{ $match->match_type }}</p>
                </div>
                <div class="space-y-2">
                    <p><span class="font-semibold text-slate-400 block sm:inline sm:w-32 sm:inline-block">Pasangan Kita:</span> <strong>{{ $match->pair->name ?? '-' }}</strong></p>
                    <p><span class="font-semibold text-slate-400 block sm:inline sm:w-32 sm:inline-block">Pemain 1 / 2:</span> {{ $match->pair->player1->name ?? '-' }} / {{ $match->pair->player2->name ?? '-' }}</p>
                    <p><span class="font-semibold text-slate-400 block sm:inline sm:w-32 sm:inline-block">Lawan:</span> <strong>{{ $match->opponent_name }}</strong></p>
                </div>
            </div>
        </div>

        {{-- Hasil Akhir Laga --}}
        <div>
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 bg-slate-100 px-3 py-1 rounded">
                II. Hasil Akhir & Skor
            </h4>
            <div class="flex items-center gap-6 p-3 bg-slate-50 border border-slate-200 rounded-lg">
                <div>
                    <span class="text-[10px] text-slate-400 block uppercase font-semibold">Hasil Pertandingan</span>
                    <span class="badge {{ $match->result === 'Menang' ? 'badge-win' : 'badge-lose' }} text-sm py-1 px-3 mt-1">
                        {{ $match->result }}
                    </span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 block uppercase font-semibold">Skor Akhir</span>
                    <p class="text-lg font-bold text-slate-800 mt-1">{{ $match->final_score ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Ringkasan Statistik Performa --}}
        <div>
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 bg-slate-100 px-3 py-1 rounded">
                III. Ringkasan Statistik Performa
            </h4>
            @php
                $stats = $match->performanceStatistic;
            @endphp
            @if($stats)
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div class="p-3 bg-slate-50 rounded border border-slate-100">
                        <span class="text-slate-400 block">Total Rally</span>
                        <span class="text-base font-bold text-slate-800">{{ $stats->total_rallies }} rally</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded border border-slate-100">
                        <span class="text-slate-400 block">Poin Diperoleh (%)</span>
                        <span class="text-base font-bold text-emerald-600">{{ $stats->pair_points }} ({{ $stats->pair_point_percentage }}%)</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded border border-slate-100">
                        <span class="text-slate-400 block">Poin Hilang (%)</span>
                        <span class="text-base font-bold text-rose-600">{{ $stats->opponent_points }} ({{ $stats->opponent_point_percentage }}%)</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded border border-slate-100">
                        <span class="text-slate-400 block">Total Unforced Error</span>
                        <span class="text-base font-bold text-rose-600">{{ $stats->pair_errors }} error</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded border border-slate-100">
                        <span class="text-slate-400 block">Jenis Error Dominan</span>
                        <span class="text-sm font-bold text-slate-800">{{ $stats->dominant_error_type ?? 'Tidak Ada' }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded border border-slate-100">
                        <span class="text-slate-400 block">Pemain Terbanyak Error</span>
                        <span class="text-sm font-bold text-rose-600">{{ $stats->mostErrorPlayer->name ?? '-' }} ({{ $stats->most_error_player_count }}x)</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded border border-slate-100">
                        <span class="text-slate-400 block">Rerata Pukulan / Rally</span>
                        <span class="text-base font-bold text-slate-800">{{ $stats->avg_stroke_count }} pukulan</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded border border-slate-100">
                        <span class="text-slate-400 block">Rerata Durasi Rally</span>
                        <span class="text-base font-bold text-slate-800">{{ $stats->avg_rally_duration }} detik</span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Hasil Evaluasi DSS --}}
        @php
            $result = $match->evaluationResult;
        @endphp
        @if($result)
            <div>
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 bg-slate-100 px-3 py-1 rounded">
                    IV. Hasil Evaluasi Rule-Based DSS
                </h4>
                <div class="text-xs text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50 p-4 rounded-lg border border-slate-100">
                    {{ $result->overall_evaluation }}
                </div>
            </div>

            <div>
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 bg-slate-100 px-3 py-1 rounded">
                    V. Fokus Perbaikan & Rekomendasi Latihan
                </h4>
                <div class="text-xs text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50 p-4 rounded-lg border border-slate-100">
                    {{ $result->improvement_focus }}
                </div>
            </div>

            {{-- Catatan Pelatih --}}
            @if($result->coach_notes)
                <div>
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 bg-slate-100 px-3 py-1 rounded">
                        VI. Catatan Taktis Tambahan Pelatih / Analis
                    </h4>
                    <p class="text-xs text-slate-600 bg-slate-50 p-4 rounded border border-slate-100 italic">
                        "{{ $result->coach_notes }}"
                    </p>
                </div>
            @endif
        @endif

        {{-- Signature area for prints --}}
        <div class="pt-8 flex justify-end text-xs">
            <div class="text-center w-64 space-y-12">
                <p>Dilaporkan Oleh,</p>
                <div class="font-bold border-t border-slate-400 pt-1">
                    {{ Auth::user()->name }}
                    <p class="font-normal text-slate-500">Pelatih / Analis Utama</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Analisis Statistik Performa')
@section('page-title', 'Analisis Statistik Performa')
@section('page-subtitle')
    Laga: {{ $match->pair->name ?? '-' }} vs {{ $match->opponent_name }} ({{ $match->match_date->format('d M Y') }})
@endsection

@section('content')
<div class="space-y-6">
    {{-- Overview Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="card p-4">
            <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider mb-1">Total Rally</span>
            <p class="text-2xl font-bold text-slate-800">{{ $stats->total_rallies }}</p>
            <p class="text-[10px] text-slate-500 mt-1">Keseluruhan set</p>
        </div>
        <div class="card p-4">
            <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider mb-1">Poin Pasangan</span>
            <p class="text-2xl font-bold text-emerald-600">{{ $stats->pair_points }}</p>
            <p class="text-[10px] text-slate-500 mt-1">Kontribusi {{ $stats->pair_point_percentage }}%</p>
        </div>
        <div class="card p-4">
            <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider mb-1">Poin Lawan</span>
            <p class="text-2xl font-bold text-rose-600">{{ $stats->opponent_points }}</p>
            <p class="text-[10px] text-slate-500 mt-1">Kontribusi {{ $stats->opponent_point_percentage }}%</p>
        </div>
        <div class="card p-4">
            <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider mb-1">Total Error Kita</span>
            <p class="text-2xl font-bold text-rose-600">{{ $stats->pair_errors }}</p>
            <p class="text-[10px] text-slate-500 mt-1">Unforced error</p>
        </div>
        <div class="card p-4 col-span-2 lg:col-span-1">
            <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider mb-1">Rerata Pukulan</span>
            <p class="text-2xl font-bold text-slate-800">{{ $stats->avg_stroke_count }}</p>
            <p class="text-[10px] text-slate-500 mt-1">Pukulan per rally</p>
        </div>
    </div>

    {{-- Graphs --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Error Type Distribution --}}
        <div class="card p-5">
            <h4 class="text-sm font-semibold text-slate-800 mb-4 uppercase tracking-wider">Sebaran Jenis Error</h4>
            <div class="flex items-center justify-center" style="height: 250px;">
                @if(isset($stats->detailed_stats['error_types_distribution']) && count($stats->detailed_stats['error_types_distribution']) > 0)
                    <canvas id="errorTypeChart"></canvas>
                @else
                    <p class="text-sm text-slate-400">Tidak ada data error.</p>
                @endif
            </div>
        </div>

        {{-- Player Error Distribution --}}
        <div class="card p-5">
            <h4 class="text-sm font-semibold text-slate-800 mb-4 uppercase tracking-wider">Kontribusi Error per Pemain</h4>
            <div class="flex items-center justify-center" style="height: 250px;">
                @if(isset($stats->detailed_stats['error_distribution']) && count($stats->detailed_stats['error_distribution']) > 0)
                    <canvas id="playerErrorChart"></canvas>
                @else
                    <p class="text-sm text-slate-400">Tidak ada data kontribusi error pemain.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Detailed Splits Table --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card p-5 lg:col-span-2">
            <h4 class="text-sm font-semibold text-slate-800 mb-4 uppercase tracking-wider">Performa Tiap Set</h4>
            <div class="overflow-x-auto">
                <table class="data-table responsive-cards text-xs">
                    <thead>
                        <tr>
                            <th>Set</th>
                            <th>Total Rally</th>
                            <th>Poin Pasangan</th>
                            <th>Poin Lawan</th>
                            <th>Error Pasangan</th>
                            <th>Hasil Set</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats->set_performance as $setNum => $set)
                            <tr>
                                <td data-label="Set" class="font-bold text-slate-700">Set {{ $setNum }}</td>
                                <td data-label="Total Rally">{{ $set['total_rallies'] }}</td>
                                <td data-label="Poin Pasangan" class="text-emerald-600 font-bold">{{ $set['pair_points'] }}</td>
                                <td data-label="Poin Lawan" class="text-rose-600 font-bold">{{ $set['opponent_points'] }}</td>
                                <td data-label="Error Pasangan" class="text-rose-600 font-medium">{{ $set['pair_errors'] }} error</td>
                                <td data-label="Hasil Set">
                                    <span class="badge {{ $set['result'] === 'Menang' ? 'badge-win' : 'badge-lose' }}">
                                        {{ $set['result'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Additional Stats --}}
        <div class="card p-5 lg:col-span-1 space-y-4">
            <h4 class="text-sm font-semibold text-slate-800 border-b border-slate-100 pb-2 mb-2 uppercase tracking-wider">
                Parameter Tambahan
            </h4>
            <div class="space-y-3 text-xs">
                <div class="flex justify-between items-start gap-2 py-1 border-b border-slate-50">
                    <span class="text-slate-400 font-medium">Jenis Error Dominan</span>
                    <span class="font-semibold text-slate-800 text-right">{{ $stats->dominant_error_type ?? 'Tidak Ada' }}</span>
                </div>
                <div class="flex justify-between items-start gap-2 py-1 border-b border-slate-50">
                    <span class="text-slate-400 font-medium">Pemain Paling Sering Error</span>
                    <span class="font-semibold text-rose-600 text-right">
                        {{ $stats->mostErrorPlayer->name ?? '-' }} ({{ $stats->most_error_player_count }} kali)
                    </span>
                </div>
                <div class="flex justify-between items-start gap-2 py-1 border-b border-slate-50">
                    <span class="text-slate-400 font-medium">Rerata Durasi Rally</span>
                    <span class="font-semibold text-slate-800 text-right">{{ $stats->avg_rally_duration }} detik</span>
                </div>
                <div class="flex justify-between items-start gap-2 py-1 border-b border-slate-50">
                    <span class="text-slate-400 font-medium">Win Rate Rally Panjang</span>
                    <span class="font-semibold text-emerald-600 text-right">{{ $stats->detailed_stats['long_rally_win_rate'] ?? 0 }}%</span>
                </div>
                <div class="flex justify-between items-start gap-2 py-1">
                    <span class="text-slate-400 font-medium">Error Poin Kritis</span>
                    <span class="font-semibold text-rose-600 text-right">
                        {{ $stats->critical_point_errors }} dari {{ $stats->total_critical_points }} poin kritis
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Navigation & Action --}}
    <div class="flex items-center justify-between flex-wrap gap-3 border-t border-slate-100 pt-4">
        <a href="{{ route('statistics.index') }}" class="btn btn-outline">
            Kembali ke Daftar
        </a>

        @if($match->status === 'Final')
            <form action="{{ route('evaluations.run', $match->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Jalankan Modul DSS Evaluasi
                </button>
            </form>
        @else
            <a href="{{ route('evaluations.show', $match->id) }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Lihat Hasil Evaluasi DSS
            </a>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    // Error Type Distribution Chart
    @if(isset($stats->detailed_stats['error_types_distribution']) && count($stats->detailed_stats['error_types_distribution']) > 0)
        const errorTypesData = @json($stats->detailed_stats['error_types_distribution']);
        new Chart(document.getElementById('errorTypeChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(errorTypesData),
                datasets: [{
                    label: 'Jumlah Kesalahan',
                    data: Object.values(errorTypesData),
                    backgroundColor: '#6366f1',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    @endif

    // Player Error Distribution Chart
    @if(isset($stats->detailed_stats['error_distribution']) && count($stats->detailed_stats['error_distribution']) > 0)
        @php
            // Map player IDs to names
            $distribution = $stats->detailed_stats['error_distribution'];
            $labels = [];
            $data = [];
            foreach($distribution as $playerId => $item) {
                $playerModel = \App\Models\Player::find($playerId);
                $labels[] = $playerModel->name ?? 'Pemain '.$playerId;
                $data[] = $item['count'];
            }
        @endphp
        new Chart(document.getElementById('playerErrorChart'), {
            type: 'pie',
            data: {
                labels: @json($labels),
                datasets: [{
                    data: @json($data),
                    backgroundColor: ['#4f46e5', '#8b5cf6', '#d946ef'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true } }
                }
            }
        });
    @endif
</script>
@endpush

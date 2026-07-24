@extends('layouts.app')

@section('title', 'Statistik Performa')
@section('page-title', 'Statistik Performa')
@section('page-subtitle', 'Analisis data performa pasangan ganda hasil olahan data rally')

@section('content')
@if($unevaluatedCount > 0)
    <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3 text-amber-800">
        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
        <div>
            <h4 class="font-semibold text-sm">Evaluasi DSS Diperlukan</h4>
            <p class="text-xs text-amber-700 mt-0.5">Terdapat <strong>{{ $unevaluatedCount }}</strong> pertandingan yang telah difinalisasi tetapi belum dievaluasi oleh modul DSS. Silakan klik tombol <strong class="text-indigo-700">Jalankan DSS</strong> pada kolom aksi untuk memproses evaluasi secara otomatis.</p>
        </div>
    </div>
@endif

<div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
        <h3 class="text-sm font-semibold text-slate-800">Daftar Statistik Pertandingan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table responsive-cards">
            <thead>
                <tr>
                    <th class="w-16">No</th>
                    <th>Tanggal</th>
                    <th>Pasangan Ganda</th>
                    <th>Lawan</th>
                    <th>Total Rally</th>
                    <th>Error Pasangan</th>
                    <th>Hasil Laga</th>
                    <th>Status DSS</th>
                    <th class="w-48 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matches as $key => $match)
                    @php
                        $stat = $match->performanceStatistic;
                    @endphp
                    <tr>
                        <td data-label="No">{{ $matches->firstItem() + $key }}</td>
                        <td data-label="Tanggal">{{ $match->match_date->format('d M Y') }}</td>
                        <td data-label="Pasangan Ganda" class="font-semibold text-slate-800">{{ $match->pair->name ?? '-' }}</td>
                        <td data-label="Lawan">{{ $match->opponent_name }}</td>
                        <td data-label="Total Rally">{{ $stat->total_rallies ?? 0 }} rally</td>
                        <td data-label="Error Pasangan" class="text-rose-600 font-medium">{{ $stat->pair_errors ?? 0 }} error</td>
                        <td data-label="Hasil Laga">
                            <span class="badge {{ $match->result === 'Menang' ? 'badge-win' : 'badge-lose' }}">
                                {{ $match->result }}
                            </span>
                        </td>
                        <td data-label="Status DSS">
                            @if($match->status === 'Final')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-300 whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 4a8 8 0 100 16 8 8 0 000-16z"/></svg>
                                    Belum Dievaluasi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300 whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Sudah Dievaluasi
                                </span>
                            @endif
                        </td>
                        <td data-label="Aksi" class="text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('statistics.show', $match->id) }}" class="btn btn-outline text-xs px-2.5 py-1" title="Analisis Statistik">
                                    Statistik
                                </a>
                                @if($match->status === 'Final')
                                    <form action="{{ route('evaluations.run', $match->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary text-xs px-2.5 py-1 font-semibold animate-pulse" title="Jalankan Evaluasi DSS">
                                            Jalankan DSS
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('evaluations.show', $match->id) }}" class="btn btn-success text-xs px-2.5 py-1 font-semibold" title="Lihat Hasil Evaluasi">
                                        Hasil DSS
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-8 text-slate-400">Belum ada data pertandingan yang difinalisasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($matches->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50">
            {{ $matches->links() }}
        </div>
    @endif
</div>
@endsection

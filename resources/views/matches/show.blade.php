@extends('layouts.app')

@section('title', 'Detail Pertandingan')
@section('page-title', 'Detail Pertandingan')
@section('page-subtitle', 'Informasi rinci data pertandingan ganda')

@section('content')
{{-- Notifikasi Draft tanpa Rally --}}
@if($match->status === 'Draft' && $match->rallies->count() === 0)
<div class="mb-5 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3">
    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0 mt-0.5">
        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
    </div>
    <div class="flex-1">
        <h4 class="text-sm font-semibold text-amber-900">Pertandingan Belum Memiliki Data Rally</h4>
        <p class="text-xs text-amber-700 mt-1">Pertandingan ini masih berstatus <strong>Draft</strong> dan belum ada data rally yang diinput. Silakan input data rally terlebih dahulu sebelum melakukan verifikasi dan finalisasi.</p>
        <a href="{{ route('rallies.index', $match->id) }}" class="inline-flex items-center gap-1.5 mt-2 text-xs font-semibold text-amber-800 hover:text-amber-900 underline underline-offset-2">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Mulai Input Rally Sekarang →
        </a>
    </div>
</div>
@endif
<div class="max-w-4xl space-y-6">
    {{-- Main Info Card --}}
    <div class="card p-6">
        <div class="flex items-center justify-between flex-wrap gap-4 border-b border-slate-100 pb-4 mb-4">
            <div>
                <span class="badge {{ $match->status === 'Draft' ? 'badge-draft' : ($match->status === 'Final' ? 'badge-final' : 'badge-evaluated') }} mb-1">
                    Status: {{ $match->status }}
                </span>
                <h3 class="text-xl font-bold text-slate-800">
                    {{ $match->pair->name ?? '-' }} vs {{ $match->opponent_name }}
                </h3>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $match->match_date->format('l, d F Y') }} · Kategori: {{ $match->pair_category === 'GD_PUTRA' ? 'Ganda Putra' : ($match->pair_category === 'GD_PUTRI' ? 'Ganda Putri' : 'Ganda Campuran') }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Hasil Laga</p>
                <span class="badge {{ $match->result === 'Menang' ? 'badge-win' : 'badge-lose' }} text-sm py-1 px-3 mt-1">
                    {{ $match->result }}
                </span>
                @if($match->final_score)
                    <p class="text-lg font-bold text-slate-700 mt-1">{{ $match->final_score }}</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Pasangan Kita --}}
            <div class="bg-slate-50 rounded-lg p-4">
                <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold mb-2">Pasangan Ganda Evaluasi</span>
                <p class="text-sm font-semibold text-slate-800">{{ $match->pair->name ?? '-' }}</p>
                <div class="text-xs text-slate-500 mt-2 space-y-1">
                    <p>Pemain 1: {{ $match->pair->player1->name ?? '-' }}</p>
                    <p>Pemain 2: {{ $match->pair->player2->name ?? '-' }}</p>
                </div>
            </div>

            {{-- Pasangan Lawan --}}
            <div class="bg-slate-50 rounded-lg p-4">
                <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold mb-2">Pasangan Lawan</span>
                <p class="text-sm font-semibold text-slate-800">{{ $match->opponent_name }}</p>
            </div>

            {{-- Detail Laga --}}
            <div class="bg-slate-50 rounded-lg p-4">
                <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold mb-2">Metadata Laga</span>
                <p class="text-sm text-slate-700"><span class="font-medium">Tipe:</span> {{ $match->match_type }}</p>
                @if($match->notes)
                    <div class="mt-2 text-xs text-slate-600">
                        <span class="font-medium block text-slate-500">Catatan:</span>
                        <p class="line-clamp-2">{{ $match->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Rallies Summary --}}
    <div class="card">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
            <h4 class="text-sm font-semibold text-slate-800 uppercase tracking-wider">Daftar Rally Pertandingan</h4>
            @if($match->status === 'Draft')
                <a href="{{ route('rallies.index', $match->id) }}" class="btn btn-primary text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Kelola / Input Rally
                </a>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="data-table responsive-cards">
                <thead>
                    <tr>
                        <th class="w-16">Set</th>
                        <th class="w-16">Rally</th>
                        <th>Skor Pasangan</th>
                        <th>Skor Lawan</th>
                        <th>Pemenang Poin</th>
                        <th>Hasil Rally</th>
                        <th>Jenis Error</th>
                        <th>Pemain Error</th>
                        <th>Stroke</th>
                        <th>Durasi</th>
                        <th>Kritis</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($match->rallies as $rally)
                        <tr>
                            <td data-label="Set">Set {{ $rally->set_number }}</td>
                            <td data-label="Rally">#{{ $rally->rally_number }}</td>
                            <td data-label="Skor Pasangan" class="font-bold text-slate-700">{{ $rally->pair_score }}</td>
                            <td data-label="Skor Lawan" class="font-bold text-slate-700">{{ $rally->opponent_score }}</td>
                            <td data-label="Pemenang Poin">
                                <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $rally->point_winner === 'Pasangan' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    {{ $rally->point_winner }}
                                </span>
                            </td>
                            <td data-label="Hasil Rally">{{ $rally->point_result }}</td>
                            <td data-label="Jenis Error">{{ $rally->error_type ?? '-' }}</td>
                            <td data-label="Pemain Error">{{ $rally->errorPlayer->name ?? '-' }}</td>
                            <td data-label="Stroke">{{ $rally->stroke_count ? $rally->stroke_count . ' pukulan' : '-' }}</td>
                            <td data-label="Durasi">{{ $rally->rally_duration ? $rally->rally_duration . ' dtk' : '-' }}</td>
                            <td data-label="Kritis">
                                @if($rally->is_critical_point)
                                    <span class="text-rose-500 font-bold">Ya</span>
                                @else
                                    <span class="text-slate-400">Tidak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-8 text-slate-400">Belum ada data rally untuk pertandingan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Actions Bar --}}
    <div class="flex items-center gap-3 flex-wrap">
        <a href="{{ route('matches.index') }}" class="btn btn-outline">Kembali</a>
        @if($match->status === 'Draft')
            <a href="{{ route('matches.edit', $match->id) }}" class="btn btn-warning text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Ubah Pertandingan
            </a>
            @if($match->rallies->count() > 0)
                <a href="{{ route('verification.show', $match->id) }}" class="btn btn-success">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Verifikasi & Finalisasi
                </a>
            @endif
        @elseif($match->status === 'Final')
            <a href="{{ route('verification.show', $match->id) }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Proses DSS Evaluasi
            </a>
        @else
            <a href="{{ route('evaluations.show', $match->id) }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Hasil Evaluasi DSS
            </a>
        @endif
    </div>
</div>
@endsection

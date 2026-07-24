@extends('layouts.app')

@section('title', 'Verifikasi Data')
@section('page-title', 'Verifikasi Data')
@section('page-subtitle', 'Periksa kelengkapan data rally sebelum melakukan finalisasi')

@section('content')
<div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
        <h3 class="text-sm font-semibold text-slate-800">Pertandingan Butuh Verifikasi</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table responsive-cards">
            <thead>
                <tr>
                    <th class="w-16">No</th>
                    <th>Tanggal</th>
                    <th>Pasangan Ganda</th>
                    <th>Lawan</th>
                    <th>Skor Akhir</th>
                    <th>Jumlah Rally</th>
                    <th>Status</th>
                    <th class="w-40 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matches as $key => $match)
                    @php $rallyCount = $match->rallies->count(); @endphp
                    <tr>
                        <td data-label="No">{{ $matches->firstItem() + $key }}</td>
                        <td data-label="Tanggal">{{ $match->match_date->format('d M Y') }}</td>
                        <td data-label="Pasangan Ganda" class="font-semibold text-slate-800">{{ $match->pair->name ?? '-' }}</td>
                        <td data-label="Lawan">{{ $match->opponent_name }}</td>
                        <td data-label="Skor Akhir">{{ $match->final_score ?? '-' }}</td>
                        <td data-label="Jumlah Rally">
                            @if($rallyCount > 0)
                                <span class="font-medium text-slate-700">{{ $rallyCount }} rally</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 4a8 8 0 100 16 8 8 0 000-16z"/></svg>
                                    Belum ada rally
                                </span>
                            @endif
                        </td>
                        <td data-label="Status">
                            <span class="badge {{ $match->status === 'Draft' ? 'badge-draft' : ($match->status === 'Final' ? 'badge-final' : 'badge-evaluated') }}">
                                {{ $match->status }}
                            </span>
                        </td>
                        <td data-label="Aksi" class="text-center lg:text-center text-right">
                            @if($match->status === 'Draft')
                                @if(!$match->isRallyInputComplete())
                                    {{-- Belum selesai input rally (belum ada pihak yang memenangkan 2 set): navigasi ke input rally terlebih dahulu --}}
                                    <a href="{{ route('rallies.index', $match->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-sm shadow-amber-600/30 transition-all hover:-translate-y-0.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Input Rally
                                    </a>
                                @else
                                    {{-- Sudah selesai input rally (sudah ada pemenang 2 set yang sah): navigasi ke proses verifikasi & finalisasi --}}
                                    <a href="{{ route('verification.show', $match->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm shadow-emerald-600/30 transition-all hover:-translate-y-0.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Verifikasi
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('statistics.show', $match->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs border border-slate-300 transition-all">
                                    Lihat Statistik
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-400">Tidak ada pertandingan yang membutuhkan verifikasi saat ini.</td>
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

@extends('layouts.app')

@section('title', 'Laporan Pertandingan')
@section('page-title', 'Laporan Pertandingan')
@section('page-subtitle', 'Pilih dan cetak laporan hasil evaluasi pertandingan ganda')

@section('content')
<div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100">
        <h3 class="text-sm font-semibold text-slate-800">Daftar Laporan Evaluasi</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table responsive-cards">
            <thead>
                <tr>
                    <th class="w-16">No</th>
                    <th>Tanggal</th>
                    <th>Pasangan Ganda</th>
                    <th>Lawan</th>
                    <th>Kategori</th>
                    <th>Skor Akhir</th>
                    <th>Hasil Laga</th>
                    <th class="w-64 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matches as $key => $match)
                    <tr>
                        <td data-label="No">{{ $matches->firstItem() + $key }}</td>
                        <td data-label="Tanggal">{{ $match->match_date->format('d M Y') }}</td>
                        <td data-label="Pasangan Ganda" class="font-semibold text-slate-800">{{ $match->pair->name ?? '-' }}</td>
                        <td data-label="Lawan">{{ $match->opponent_name }}</td>
                        <td data-label="Kategori">
                            <span class="px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-600">
                                {{ $match->pair_category === 'GD_PUTRA' ? 'Ganda Putra' : ($match->pair_category === 'GD_PUTRI' ? 'Ganda Putri' : 'Ganda Campuran') }}
                            </span>
                        </td>
                        <td data-label="Skor Akhir">{{ $match->final_score ?? '-' }}</td>
                        <td data-label="Hasil Laga">
                            <span class="badge {{ $match->result === 'Menang' ? 'badge-win' : 'badge-lose' }}">
                                {{ $match->result }}
                            </span>
                        </td>
                        <td data-label="Aksi" class="text-center">
                            <div class="flex items-center justify-end lg:justify-center gap-2">
                                <a href="{{ route('reports.show', $match->id) }}" class="btn btn-outline text-xs px-2.5 py-1" title="Lihat detail laporan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Detail
                                </a>
                                <a href="{{ route('reports.print', $match->id) }}?action=pdf" target="_blank" class="btn text-xs px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 hover:border-rose-300 transition-colors" title="Unduh sebagai PDF">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    PDF
                                </a>
                                <a href="{{ route('reports.print', $match->id) }}?action=print" target="_blank" class="btn btn-primary text-xs px-2.5 py-1" title="Cetak laporan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    Cetak
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-400">Belum ada pertandingan yang selesai dievaluasi untuk dicetak laporannya.</td>
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

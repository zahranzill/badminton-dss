@extends('layouts.app')

@section('title', 'Detail Pasangan Ganda')
@section('page-title', 'Detail Pasangan Ganda')
@section('page-subtitle', 'Informasi lengkap mengenai pasangan ganda')

@section('content')
<div class="max-w-3xl space-y-6">
    {{-- Main Info --}}
    <div class="card p-6 flex flex-col md:flex-row items-start gap-6">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold text-xl shadow-lg flex-shrink-0">
            {{ strtoupper(substr($pair->name, 0, 2)) }}
        </div>
        <div class="flex-1 space-y-3">
            <div>
                <h3 class="text-xl font-bold text-slate-800">{{ $pair->name }}</h3>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $pair->pair_type === 'GD_PUTRA' ? 'Ganda Putra' : ($pair->pair_type === 'GD_PUTRI' ? 'Ganda Putri' : 'Ganda Campuran') }}
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 border-t border-slate-100 pt-3">
                <div>
                    <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold">Pemain 1</span>
                    <a href="{{ route('players.show', $pair->player1->id ?? '#') }}" class="text-sm text-primary-600 font-medium hover:underline">
                        {{ $pair->player1->name ?? '-' }}
                    </a>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold">Pemain 2</span>
                    <a href="{{ route('players.show', $pair->player2->id ?? '#') }}" class="text-sm text-primary-600 font-medium hover:underline">
                        {{ $pair->player2->name ?? '-' }}
                    </a>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold">Status Keaktifan</span>
                    <span class="badge {{ $pair->is_active ? 'badge-active' : 'badge-inactive' }} mt-1">
                        {{ $pair->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>

            @if($pair->description)
                <div class="border-t border-slate-100 pt-3">
                    <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold">Keterangan / Catatan</span>
                    <p class="text-sm text-slate-600 mt-1">{{ $pair->description }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Match History --}}
    <div class="card overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
            <h4 class="text-sm font-semibold text-slate-800 uppercase tracking-wider">Riwayat Pertandingan</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-16">No</th>
                        <th>Tanggal</th>
                        <th>Lawan</th>
                        <th>Kategori</th>
                        <th>Tipe Laga</th>
                        <th>Skor</th>
                        <th>Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pair->matches as $key => $match)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $match->match_date->format('d M Y') }}</td>
                            <td class="font-medium text-slate-800">{{ $match->opponent_name }}</td>
                            <td>
                                {{ $match->pair_category === 'GD_PUTRA' ? 'Ganda Putra' : ($match->pair_category === 'GD_PUTRI' ? 'Ganda Putri' : 'Ganda Campuran') }}
                            </td>
                            <td>{{ $match->match_type }}</td>
                            <td>{{ $match->final_score ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $match->result === 'Menang' ? 'badge-win' : 'badge-lose' }}">
                                    {{ $match->result }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-slate-400">Belum ada riwayat pertandingan untuk pasangan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('pairs.index') }}" class="btn btn-outline">Kembali</a>
        <a href="{{ route('pairs.edit', $pair->id) }}" class="btn btn-warning text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Ubah Data
        </a>
    </div>
</div>
@endsection

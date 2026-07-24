@extends('layouts.app')

@section('title', 'Detail Pemain')
@section('page-title', 'Detail Pemain')
@section('page-subtitle', 'Informasi mendalam mengenai data pemain')

@section('content')
<div class="max-w-3xl space-y-6">
    {{-- Main Info --}}
    <div class="card p-6 flex flex-col md:flex-row items-start gap-6">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold text-2xl shadow-lg flex-shrink-0">
            {{ strtoupper(substr($player->name, 0, 1)) }}
        </div>
        <div class="flex-1 space-y-3">
            <div>
                <h3 class="text-xl font-bold text-slate-800">{{ $player->name }}</h3>
                <p class="text-sm text-slate-500 mt-1">{{ $player->category ?? 'Tanpa Kategori' }}</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-3">
                <div>
                    <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold">Jenis Kelamin</span>
                    <span class="text-sm text-slate-700 font-medium">{{ $player->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold">Status Keaktifan</span>
                    <span class="badge {{ $player->is_active ? 'badge-active' : 'badge-inactive' }} mt-1">
                        {{ $player->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Association Info --}}
    <div class="card p-6">
        <h4 class="text-sm font-semibold text-slate-800 mb-4 uppercase tracking-wider">Pasangan Ganda Terkait</h4>
        <div class="divide-y divide-slate-100">
            @php
                $associatedPairs = $player->pairsAsPlayer1->merge($player->pairsAsPlayer2);
            @endphp
            @forelse($associatedPairs as $pair)
                <div class="py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">{{ $pair->name }}</p>
                        <p class="text-xs text-slate-500">
                            Partner: {{ $pair->player_1_id == $player->id ? ($pair->player2->name ?? '-') : ($pair->player1->name ?? '-') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-600">
                            {{ $pair->pair_type === 'GD_PUTRA' ? 'Ganda Putra' : ($pair->pair_type === 'GD_PUTRI' ? 'Ganda Putri' : 'Ganda Campuran') }}
                        </span>
                        <a href="{{ route('pairs.show', $pair->id) }}" class="text-xs text-primary-600 hover:underline font-semibold">Lihat</a>
                    </div>
                </div>
            @empty
                <p class="text-center py-6 text-sm text-slate-400">Pemain belum dipasangkan dengan siapa pun.</p>
            @endforelse
        </div>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('players.index') }}" class="btn btn-outline">Kembali</a>
        <a href="{{ route('players.edit', $player->id) }}" class="btn btn-warning text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Ubah Data
        </a>
    </div>
</div>
@endsection

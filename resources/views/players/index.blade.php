@extends('layouts.app')

@section('title', 'Data Pemain')
@section('page-title', 'Data Pemain')
@section('page-subtitle', 'Kelola daftar pemain bulutangkis')

@section('content')
<div class="space-y-4">
    {{-- Header Actions & Filter --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('players.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            <div>
                <label for="search" class="form-label text-xs">Cari Pemain</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       class="form-input" placeholder="Nama / Kategori...">
            </div>
            <div>
                <label for="gender" class="form-label text-xs">Jenis Kelamin</label>
                <select id="gender" name="gender" class="form-input">
                    <option value="">Semua</option>
                    <option value="L" {{ request('gender') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                    <option value="P" {{ request('gender') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                </select>
            </div>
            <div>
                <label for="status" class="form-label text-xs">Status</label>
                <select id="status" name="status" class="form-input">
                    <option value="">Semua</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary flex-1 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'gender', 'status']))
                    <a href="{{ route('players.index') }}" class="btn btn-outline">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="card overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
            <h3 class="text-sm font-semibold text-slate-800">Daftar Pemain</h3>
            <a href="{{ route('players.create') }}" class="btn btn-primary text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Pemain
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table responsive-cards">
                <thead>
                    <tr>
                        <th class="w-16">No</th>
                        <th>Nama Pemain</th>
                        <th>Jenis Kelamin</th>
                        <th>Kategori / Keterangan</th>
                        <th class="w-32">Status</th>
                        <th class="w-36 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($players as $key => $player)
                        <tr>
                            <td data-label="No">{{ $players->firstItem() + $key }}</td>
                            <td data-label="Nama Pemain" class="font-medium text-slate-800">{{ $player->name }}</td>
                            <td data-label="Jenis Kelamin">
                                <span class="whitespace-nowrap px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $player->gender === 'L' ? 'bg-indigo-50 text-indigo-700' : 'bg-pink-50 text-pink-700' }}">
                                    {{ $player->gender === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' }}
                                </span>
                            </td>
                            <td data-label="Kategori">{{ $player->category ?? '-' }}</td>
                            <td data-label="Status">
                                <span class="badge {{ $player->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $player->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td data-label="Aksi" class="text-center">
                                <div class="flex items-center justify-center lg:justify-center justify-end gap-2">
                                    <a href="{{ route('players.show', $player->id) }}" class="p-1 text-slate-500 hover:text-primary-600 transition-colors" title="Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('players.edit', $player->id) }}" class="p-1 text-slate-500 hover:text-amber-600 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button onclick="confirmDelete('{{ route('players.destroy', $player->id) }}', 'Apakah Anda yakin ingin menghapus pemain {{ $player->name }}?')" class="p-1 text-slate-500 hover:text-rose-600 transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400">Tidak ada data pemain ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($players->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50">
                {{ $players->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

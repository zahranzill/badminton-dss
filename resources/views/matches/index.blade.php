@extends('layouts.app')

@section('title', 'Data Pertandingan')
@section('page-title', 'Data Pertandingan')
@section('page-subtitle', 'Kelola riwayat dan draf pertandingan ganda')

@section('content')
<div class="space-y-4">
    {{-- Header Actions & Filter --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('matches.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            <div>
                <label for="search" class="form-label text-xs">Cari Lawan / Pasangan</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       class="form-input" placeholder="Lawan atau Pasangan...">
            </div>
            <div>
                <label for="status" class="form-label text-xs">Status Data</label>
                <select id="status" name="status" class="form-input">
                    <option value="">Semua</option>
                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Final" {{ request('status') == 'Final' ? 'selected' : '' }}>Final</option>
                    <option value="Dievaluasi" {{ request('status') == 'Dievaluasi' ? 'selected' : '' }}>Dievaluasi</option>
                </select>
            </div>
            <div>
                <label for="result" class="form-label text-xs">Hasil Laga</label>
                <select id="result" name="result" class="form-input">
                    <option value="">Semua</option>
                    <option value="Menang" {{ request('result') == 'Menang' ? 'selected' : '' }}>Menang</option>
                    <option value="Kalah" {{ request('result') == 'Kalah' ? 'selected' : '' }}>Kalah</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary flex-1 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'status', 'result']))
                    <a href="{{ route('matches.index') }}" class="btn btn-outline">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="card overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
            <h3 class="text-sm font-semibold text-slate-800">Daftar Pertandingan</h3>
            <a href="{{ route('matches.create') }}" class="btn btn-primary text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Laga Baru
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table responsive-cards">
                <thead>
                    <tr>
                        <th class="w-16">No</th>
                        <th>Tanggal</th>
                        <th>Pasangan Ganda</th>
                        <th>Lawan</th>
                        <th>Jenis Pertandingan</th>
                        <th>Kategori</th>
                        <th>Skor Akhir</th>
                        <th>Hasil</th>
                        <th>Status</th>
                        <th class="w-40 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($matches as $key => $match)
                        <tr>
                            <td data-label="No">{{ $matches->firstItem() + $key }}</td>
                            <td data-label="Tanggal">{{ $match->match_date->format('d M Y') }}</td>
                            <td data-label="Pasangan Ganda" class="font-semibold text-slate-800">{{ $match->pair->name ?? '-' }}</td>
                            <td data-label="Lawan" class="font-medium text-slate-700">{{ $match->opponent_name }}</td>
                            <td data-label="Jenis Pertandingan">{{ $match->match_type }}</td>
                            <td data-label="Kategori">
                                <span class="whitespace-nowrap px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-600">
                                    {{ $match->pair_category === 'GD_PUTRA' ? 'Ganda Putra' : ($match->pair_category === 'GD_PUTRI' ? 'Ganda Putri' : 'Ganda Campuran') }}
                                </span>
                            </td>
                            <td data-label="Skor Akhir">{{ $match->final_score ?? '-' }}</td>
                            <td data-label="Hasil">
                                <span class="badge {{ $match->result === 'Menang' ? 'badge-win' : 'badge-lose' }}">
                                    {{ $match->result }}
                                </span>
                            </td>
                            <td data-label="Status">
                                <div class="flex flex-col gap-1 items-start">
                                    <span class="badge {{ $match->status === 'Draft' ? 'badge-draft' : ($match->status === 'Final' ? 'badge-final' : 'badge-evaluated') }}">
                                        {{ $match->status }}
                                    </span>
                                    @if($match->status === 'Draft' && $match->rallies_count === 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 4a8 8 0 100 16 8 8 0 000-16z"/></svg>
                                            Belum ada rally
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td data-label="Aksi" class="text-center">
                                <div class="flex items-center justify-center lg:justify-center justify-end gap-1">
                                    {{-- Lihat Detail --}}
                                    <a href="{{ route('matches.show', $match->id) }}" class="p-1 text-slate-500 hover:text-primary-600 transition-colors" title="Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    {{-- Input Rally (Hanya jika Draft) --}}
                                    @if($match->status === 'Draft')
                                        <a href="{{ route('rallies.index', $match->id) }}" class="p-1 text-slate-500 hover:text-indigo-600 transition-colors" title="Input Data Rally">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('matches.edit', $match->id) }}" class="p-1 text-slate-500 hover:text-amber-600 transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <button onclick="confirmDelete('{{ route('matches.destroy', $match->id) }}', 'Apakah Anda yakin ingin menghapus data pertandingan ini?')" class="p-1 text-slate-500 hover:text-rose-600 transition-colors" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @else
                                        {{-- Tautan Hasil Evaluasi / Statistik jika status Final atau Dievaluasi --}}
                                        @if($match->status === 'Final')
                                            <a href="{{ route('verification.show', $match->id) }}" class="p-1 text-emerald-600 hover:text-emerald-700 transition-colors" title="Proses Evaluasi">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </a>
                                        @else
                                            <a href="{{ route('evaluations.show', $match->id) }}" class="p-1 text-primary-600 hover:text-primary-700 transition-colors" title="Lihat Hasil Evaluasi">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-8 text-slate-400">Tidak ada data pertandingan ditemukan.</td>
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
</div>
@endsection

@extends('layouts.app')

@section('title', 'Riwayat Evaluasi')
@section('page-title', 'Riwayat Evaluasi')
@section('page-subtitle', 'Lihat kembali hasil analisis DSS dari pertandingan ganda')

@section('content')
<div class="space-y-4">
    {{-- Search & Filters --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('evaluation-history.index') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3 items-end">
            <div>
                <label for="search" class="form-label text-xs">Cari Lawan</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       class="form-input text-xs" placeholder="Nama Lawan...">
            </div>
            <div>
                <label for="pair_id" class="form-label text-xs">Pasangan Ganda</label>
                <select id="pair_id" name="pair_id" class="form-input text-xs">
                    <option value="">Semua</option>
                    @foreach($pairs as $pair)
                        <option value="{{ $pair->id }}" {{ request('pair_id') == $pair->id ? 'selected' : '' }}>
                            {{ $pair->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="result" class="form-label text-xs">Hasil Laga</label>
                <select id="result" name="result" class="form-input text-xs">
                    <option value="">Semua</option>
                    <option value="Menang" {{ request('result') == 'Menang' ? 'selected' : '' }}>Menang</option>
                    <option value="Kalah" {{ request('result') == 'Kalah' ? 'selected' : '' }}>Kalah</option>
                </select>
            </div>
            <div class="sm:col-span-2 grid grid-cols-2 gap-2">
                <div>
                    <label for="start_date" class="form-label text-xs">Dari Tanggal</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="form-input text-xs">
                </div>
                <div>
                    <label for="end_date" class="form-label text-xs">Hingga Tanggal</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="form-input text-xs">
                </div>
            </div>
            <div class="sm:col-span-5 flex justify-end gap-2 mt-2">
                <button type="submit" class="btn btn-primary text-xs px-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter Data
                </button>
                @if(request()->anyFilled(['search', 'pair_id', 'result', 'start_date', 'end_date']))
                    <a href="{{ route('evaluation-history.index') }}" class="btn btn-outline text-xs">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="card overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-800">Daftar Riwayat Evaluasi</h3>
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
                        <th>Hasil</th>
                        <th>Kesimpulan Evaluasi</th>
                        <th class="w-36 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($matches as $key => $match)
                        <tr>
                            <td data-label="No">{{ $matches->firstItem() + $key }}</td>
                            <td data-label="Tanggal">{{ $match->match_date->format('d M Y') }}</td>
                            <td data-label="Pasangan Ganda" class="font-semibold text-slate-800">{{ $match->pair->name ?? '-' }}</td>
                            <td data-label="Lawan" class="font-medium text-slate-700">{{ $match->opponent_name }}</td>
                            <td data-label="Skor Akhir">{{ $match->final_score ?? '-' }}</td>
                            <td data-label="Hasil">
                                <span class="badge {{ $match->result === 'Menang' ? 'badge-win' : 'badge-lose' }}">
                                    {{ $match->result }}
                                </span>
                            </td>
                            <td data-label="Kesimpulan Evaluasi">
                                <p class="text-xs text-slate-500 line-clamp-2 text-right lg:text-left" title="{{ $match->evaluationResult->overall_evaluation ?? '-' }}">
                                    {{ Str::limit($match->evaluationResult->overall_evaluation ?? '-', 100) }}
                                </p>
                            </td>
                            <td data-label="Aksi" class="text-center">
                                <div class="flex items-center justify-end lg:justify-center gap-1.5">
                                    <a href="{{ route('evaluations.show', $match->id) }}" class="btn btn-primary text-xs px-2.5 py-1">
                                        Lihat Hasil
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-400">Belum ada riwayat hasil evaluasi.</td>
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

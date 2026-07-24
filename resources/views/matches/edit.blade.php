@extends('layouts.app')

@section('title', 'Ubah Pertandingan')
@section('page-title', 'Ubah Pertandingan')
@section('page-subtitle', 'Perbarui data draf pertandingan')

@section('content')
<div class="max-w-2xl">
    <div class="card p-6">
        <form method="POST" action="{{ route('matches.update', $match->id) }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Tanggal Pertandingan --}}
                    <div>
                        <label for="match_date" class="form-label">Tanggal Pertandingan <span class="text-rose-500">*</span></label>
                        <input type="date" id="match_date" name="match_date" value="{{ old('match_date', $match->match_date->format('Y-m-d')) }}" 
                               class="form-input @error('match_date') error @enderror" required>
                        @error('match_date')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Pertandingan --}}
                    <div>
                        <label for="match_type" class="form-label">Jenis Pertandingan <span class="text-rose-500">*</span></label>
                        <select id="match_type" name="match_type" class="form-input @error('match_type') error @enderror" required>
                            <option value="Persahabatan" {{ old('match_type', $match->match_type) === 'Persahabatan' ? 'selected' : '' }}>Persahabatan</option>
                            <option value="Turnamen" {{ old('match_type', $match->match_type) === 'Turnamen' ? 'selected' : '' }}>Turnamen</option>
                            <option value="Latih Tanding" {{ old('match_type', $match->match_type) === 'Latih Tanding' ? 'selected' : '' }}>Latih Tanding</option>
                            <option value="Lainnya" {{ old('match_type', $match->match_type) === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('match_type')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Pasangan Ganda --}}
                    <div>
                        <label for="pair_id" class="form-label">Pasangan Ganda Evaluasi <span class="text-rose-500">*</span></label>
                        <select id="pair_id" name="pair_id" class="form-input @error('pair_id') error @enderror" required>
                            @foreach($pairs as $pair)
                                <option value="{{ $pair->id }}" {{ old('pair_id', $match->pair_id) == $pair->id ? 'selected' : '' }}>
                                    {{ $pair->name }} ({{ $pair->pair_type === 'GD_PUTRA' ? 'Ganda Putra' : ($pair->pair_type === 'GD_PUTRI' ? 'Ganda Putri' : 'Ganda Campuran') }})
                                </option>
                            @endforeach
                        </select>
                        @error('pair_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori Ganda --}}
                    <div>
                        <label for="pair_category" class="form-label">Kategori Ganda Laga <span class="text-rose-500">*</span></label>
                        <select id="pair_category" name="pair_category" class="form-input @error('pair_category') error @enderror" required>
                            <option value="GD_PUTRA" {{ old('pair_category', $match->pair_category) === 'GD_PUTRA' ? 'selected' : '' }}>Ganda Putra (GD_PUTRA)</option>
                            <option value="GD_PUTRI" {{ old('pair_category', $match->pair_category) === 'GD_PUTRI' ? 'selected' : '' }}>Ganda Putri (GD_PUTRI)</option>
                            <option value="GD_CAMPURAN" {{ old('pair_category', $match->pair_category) === 'GD_CAMPURAN' ? 'selected' : '' }}>Ganda Campuran (GD_CAMPURAN)</option>
                        </select>
                        @error('pair_category')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Nama Lawan --}}
                    <div>
                        <label for="opponent_name" class="form-label">Nama Pasangan Lawan <span class="text-rose-500">*</span></label>
                        <input type="text" id="opponent_name" name="opponent_name" value="{{ old('opponent_name', $match->opponent_name) }}" 
                               class="form-input @error('opponent_name') error @enderror" required>
                        @error('opponent_name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hasil Pertandingan --}}
                    <div>
                        <label for="result" class="form-label">Hasil Pertandingan <span class="text-rose-500">*</span></label>
                        <select id="result" name="result" class="form-input @error('result') error @enderror" required>
                            <option value="Menang" {{ old('result', $match->result) === 'Menang' ? 'selected' : '' }}>Menang</option>
                            <option value="Kalah" {{ old('result', $match->result) === 'Kalah' ? 'selected' : '' }}>Kalah</option>
                        </select>
                        @error('result')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Skor Akhir --}}
                <div>
                    <label for="final_score" class="form-label">Skor Akhir</label>
                    <input type="text" id="final_score" name="final_score" value="{{ old('final_score', $match->final_score) }}" 
                           class="form-input @error('final_score') error @enderror" placeholder="Contoh: 21-19, 18-21, 21-15">
                    <p class="text-xs text-slate-400 mt-1">Pisahkan skor per set dengan koma (,)</p>
                    @error('final_score')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan Pertandingan --}}
                <div>
                    <label for="notes" class="form-label">Catatan Pertandingan</label>
                    <textarea id="notes" name="notes" rows="3" 
                              class="form-input @error('notes') error @enderror">{{ old('notes', $match->notes) }}</textarea>
                    @error('notes')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('matches.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

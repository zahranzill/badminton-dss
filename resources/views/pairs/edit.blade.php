@extends('layouts.app')

@section('title', 'Ubah Pasangan Ganda')
@section('page-title', 'Ubah Pasangan Ganda')
@section('page-subtitle', 'Perbarui data pasangan ganda bulutangkis')

@section('content')
<div class="max-w-2xl">
    <div class="card p-6">
        <form method="POST" action="{{ route('pairs.update', $pair->id) }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                {{-- Nama Pasangan --}}
                <div>
                    <label for="name" class="form-label">Nama Pasangan Ganda <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $pair->name) }}" 
                           class="form-input @error('name') error @enderror" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Pemain 1 --}}
                    <div>
                        <label for="player_1_id" class="form-label">Pemain 1 <span class="text-rose-500">*</span></label>
                        <select id="player_1_id" name="player_1_id" class="form-input @error('player_1_id') error @enderror" required>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}" {{ old('player_1_id', $pair->player_1_id) == $player->id ? 'selected' : '' }}>
                                    {{ $player->name }} ({{ $player->gender }})
                                </option>
                            @endforeach
                        </select>
                        @error('player_1_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pemain 2 --}}
                    <div>
                        <label for="player_2_id" class="form-label">Pemain 2 <span class="text-rose-500">*</span></label>
                        <select id="player_2_id" name="player_2_id" class="form-input @error('player_2_id') error @enderror" required>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}" {{ old('player_2_id', $pair->player_2_id) == $player->id ? 'selected' : '' }}>
                                    {{ $player->name }} ({{ $player->gender }})
                                </option>
                            @endforeach
                        </select>
                        @error('player_2_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Jenis Ganda --}}
                <div>
                    <label for="pair_type" class="form-label">Jenis Ganda <span class="text-rose-500">*</span></label>
                    <select id="pair_type" name="pair_type" class="form-input @error('pair_type') error @enderror" required>
                        <option value="GD_PUTRA" {{ old('pair_type', $pair->pair_type) === 'GD_PUTRA' ? 'selected' : '' }}>Ganda Putra (GD_PUTRA)</option>
                        <option value="GD_PUTRI" {{ old('pair_type', $pair->pair_type) === 'GD_PUTRI' ? 'selected' : '' }}>Ganda Putri (GD_PUTRI)</option>
                        <option value="GD_CAMPURAN" {{ old('pair_type', $pair->pair_type) === 'GD_CAMPURAN' ? 'selected' : '' }}>Ganda Campuran (GD_CAMPURAN)</option>
                    </select>
                    @error('pair_type')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="description" class="form-label">Keterangan</label>
                    <textarea id="description" name="description" rows="3" 
                              class="form-input @error('description') error @enderror">{{ old('description', $pair->description) }}</textarea>
                    @error('description')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Aktif --}}
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $pair->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                    <label for="is_active" class="ml-2 text-sm text-slate-700 font-medium">Pasangan Aktif</label>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('pairs.index') }}" class="btn btn-outline">Batal</a>
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

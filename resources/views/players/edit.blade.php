@extends('layouts.app')

@section('title', 'Ubah Data Pemain')
@section('page-title', 'Ubah Data Pemain')
@section('page-subtitle', 'Perbarui data pemain bulutangkis')

@section('content')
<div class="max-w-2xl">
    <div class="card p-6">
        <form method="POST" action="{{ route('players.update', $player->id) }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                {{-- Nama Pemain --}}
                <div>
                    <label for="name" class="form-label">Nama Pemain <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $player->name) }}" 
                           class="form-input @error('name') error @enderror" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label for="gender" class="form-label">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select id="gender" name="gender" class="form-input @error('gender') error @enderror" required>
                        <option value="L" {{ old('gender', $player->gender) === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('gender', $player->gender) === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                    @error('gender')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori / Keterangan --}}
                <div>
                    <label for="category" class="form-label">Kategori / Keterangan</label>
                    <input type="text" id="category" name="category" value="{{ old('category', $player->category) }}" 
                           class="form-input @error('category') error @enderror">
                    @error('category')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Aktif --}}
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $player->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                    <label for="is_active" class="ml-2 text-sm text-slate-700 font-medium">Pemain Aktif</label>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('players.index') }}" class="btn btn-outline">Batal</a>
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

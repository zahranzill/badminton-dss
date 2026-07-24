@extends('layouts.app')

@section('title', 'Tambah Pemain Baru')
@section('page-title', 'Tambah Pemain Baru')
@section('page-subtitle', 'Tambahkan data pemain bulutangkis baru ke dalam sistem')

@section('content')
<div class="max-w-2xl">
    <div class="card p-6">
        <form method="POST" action="{{ route('players.store') }}">
            @csrf

            <div class="space-y-4">
                {{-- Nama Pemain --}}
                <div>
                    <label for="name" class="form-label">Nama Pemain <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                           class="form-input @error('name') error @enderror" placeholder="Contoh: Kevin Sanjaya" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label for="gender" class="form-label">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select id="gender" name="gender" class="form-input @error('gender') error @enderror" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                    @error('gender')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori / Keterangan --}}
                <div>
                    <label for="category" class="form-label">Kategori / Keterangan</label>
                    <input type="text" id="category" name="category" value="{{ old('category') }}" 
                           class="form-input @error('category') error @enderror" placeholder="Contoh: Pratama, Utama, Ganda Putra, Ganda Campuran">
                    @error('category')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Aktif --}}
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked
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
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

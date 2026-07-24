@extends('layouts.app')

@section('title', 'Profil Akun')
@section('page-title', 'Profil Akun')
@section('page-subtitle', 'Kelola informasi akun Anda')

@section('content')
<div class="max-w-3xl space-y-6">
    {{-- Update Profile --}}
    <div class="card p-6">
        <h3 class="text-base font-semibold text-slate-800 mb-1">Informasi Profil</h3>
        <p class="text-sm text-slate-500 mb-5">Perbarui nama dan alamat email akun Anda.</p>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                           class="form-input @error('name') error @enderror" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                           class="form-input @error('email') error @enderror" required>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="card p-6">
        <h3 class="text-base font-semibold text-slate-800 mb-1">Ubah Password</h3>
        <p class="text-sm text-slate-500 mb-5">Pastikan akun Anda menggunakan password yang kuat dan aman.</p>

        <form method="POST" action="{{ route('profile.password') }}">
            @csrf
            @method('PUT')

            <div class="space-y-4 mb-4">
                <div>
                    <label for="current_password" class="form-label">Password Saat Ini</label>
                    <input type="password" id="current_password" name="current_password"
                           class="form-input @error('current_password') error @enderror" required>
                    @error('current_password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" id="password" name="password"
                               class="form-input @error('password') error @enderror" required>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-input" required>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Ubah Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

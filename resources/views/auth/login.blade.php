@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="w-full max-w-md">
    {{-- Logo Card --}}
    <div class="text-center mb-8" style="animation: fadeSlideUp 0.4s ease-out;">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full overflow-hidden shadow-2xl shadow-amber-500/20 mb-4 ring-3 ring-amber-500/30 bg-black">
            <img src="{{ asset('images/logo-garles.png') }}?v={{ time() }}" alt="PB Garles" class="w-full h-full object-cover">
        </div>
        <h1 class="text-2xl font-bold text-white">PB Garles DSS</h1>
        <p class="text-slate-400 text-sm mt-1">Decision Support System — Evaluasi Ganda</p>
    </div>

    {{-- Login Form --}}
    <div class="login-glass rounded-2xl p-6 sm:p-8">
        {{-- Net divider --}}
        <div class="net-pattern mb-6"></div>

        <h2 class="text-lg font-semibold text-slate-800 mb-1">Masuk ke Akun</h2>
        <p class="text-sm text-slate-500 mb-6">Silakan masuk untuk mengakses sistem evaluasi</p>

        @if($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-rose-50 border border-rose-200">
                <div class="flex items-center gap-2 text-sm text-rose-700">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       class="form-input @error('email') error @enderror"
                       placeholder="nama@email.com">
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                           class="form-input @error('password') error @enderror"
                           placeholder="••••••••">
                    <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                        <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center mb-6">
                <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                <label for="remember" class="ml-2 text-sm text-slate-600">Ingat saya</label>
            </div>

            <button type="submit" class="btn btn-primary w-full justify-center py-2.5 text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Masuk
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-slate-500 mt-6">
        &copy; {{ date('Y') }} Badminton DSS. Sistem Evaluasi Pertandingan Ganda.
    </p>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
@endsection

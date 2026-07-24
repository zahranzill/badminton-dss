@extends('layouts.app')

@section('title', 'Tambah Aturan Evaluasi')
@section('page-title', 'Tambah Aturan Evaluasi')
@section('page-subtitle', 'Tambahkan aturan penilaian baru yang akan digunakan sistem untuk menganalisis pertandingan')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form method="POST" action="{{ route('evaluation-rules.store') }}">
            @csrf

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Nama Aturan --}}
                    <div>
                        <label for="rule_name" class="form-label">Nama Aturan <span class="text-rose-500">*</span></label>
                        <input type="text" id="rule_name" name="rule_name" value="{{ old('rule_name') }}" 
                               class="form-input @error('rule_name') error @enderror" placeholder="Contoh: Netting Lemah" required>
                        @error('rule_name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Indikator --}}
                    <div>
                        <label for="indicator" class="form-label">Indikator <span class="text-rose-500">*</span></label>
                        <input type="text" id="indicator" name="indicator" value="{{ old('indicator') }}" 
                               class="form-input @error('indicator') error @enderror" placeholder="Contoh: Kesalahan Netting" required>
                        @error('indicator')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Deskripsi Kondisi Bahasa Manusia --}}
                <div>
                    <label for="condition_logic" class="form-label">Kapan Aturan Ini Berlaku? <span class="text-rose-500">*</span></label>
                    <input type="text" id="condition_logic" name="condition_logic" value="{{ old('condition_logic') }}" 
                           class="form-input @error('condition_logic') error @enderror" placeholder="Contoh: Jika pasangan lebih dari 30% melakukan kesalahan sendiri" required>
                    <p class="text-xs text-slate-400 mt-1">Tuliskan kondisi dalam kalimat yang mudah dipahami</p>
                    @error('condition_logic')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Parameter Teknis --}}
                <div class="p-4 bg-slate-50 border border-slate-100 rounded-lg space-y-4">
                    <span class="text-xs font-bold text-slate-500 block uppercase tracking-wider">⚙️ Pengaturan Teknis (untuk sistem)</span>
                    <p class="text-xs text-slate-400 -mt-2">Bagian ini digunakan sistem untuk mencocokkan data secara otomatis. Isi sesuai kebutuhan.</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        {{-- Param --}}
                        <div>
                            <label for="condition_param" class="form-label text-xs">Ukuran yang Dicek <span class="text-rose-500">*</span></label>
                            <select id="condition_param" name="condition_param" class="form-input text-xs" required>
                                <option value="">-- Pilih --</option>
                                @foreach($params as $key => $val)
                                    <option value="{{ $key }}" {{ old('condition_param') === $key ? 'selected' : '' }}>
                                        {{ $val }}
                                    </option>
                                @endforeach
                            </select>
                            @error('condition_param')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Operator --}}
                        <div>
                            <label for="condition_operator" class="form-label text-xs">Perbandingan <span class="text-rose-500">*</span></label>
                            <select id="condition_operator" name="condition_operator" class="form-input text-xs" required>
                                <option value="">-- Pilih --</option>
                                @foreach($operators as $key => $val)
                                    <option value="{{ $key }}" {{ old('condition_operator') === $key ? 'selected' : '' }}>
                                        {{ $val }}
                                    </option>
                                @endforeach
                            </select>
                            @error('condition_operator')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Value --}}
                        <div>
                            <label for="condition_value" class="form-label text-xs">Batas Nilainya <span class="text-rose-500">*</span></label>
                            <input type="text" id="condition_value" name="condition_value" value="{{ old('condition_value') }}" 
                                   class="form-input text-xs" placeholder="Misal: 30 atau Net atau true" required>
                            @error('condition_value')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Catatan Evaluasi --}}
                <div>
                    <label for="evaluation_result" class="form-label">Catatan Evaluasi yang Akan Ditampilkan <span class="text-rose-500">*</span></label>
                    <textarea id="evaluation_result" name="evaluation_result" rows="3" 
                              class="form-input @error('evaluation_result') error @enderror" placeholder="Tuliskan catatan evaluasi dalam bahasa pelatih yang mudah dipahami..." required>{{ old('evaluation_result') }}</textarea>
                    @error('evaluation_result')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alasan Evaluasi --}}
                <div>
                    <label for="evaluation_reason" class="form-label">Kalimat Alasan Evaluasi <span class="text-rose-500">*</span></label>
                    <textarea id="evaluation_reason" name="evaluation_reason" rows="2" 
                              class="form-input @error('evaluation_reason') error @enderror" placeholder="Contoh: Dari total rally, [actual_value]% diakhiri dengan kesalahan sendiri." required>{{ old('evaluation_reason') }}</textarea>
                    <p class="text-xs text-slate-400 mt-1">Gunakan <code class='bg-slate-100 px-1 rounded text-slate-600'>[actual_value]</code> untuk angka aktual, dan <code class='bg-slate-100 px-1 rounded text-slate-600'>[player_name]</code> untuk nama pemain.</p>
                    @error('evaluation_reason')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Priority --}}
                    <div>
                        <label for="priority" class="form-label">Prioritas Urutan Pengecekan <span class="text-rose-500">*</span></label>
                        <input type="number" id="priority" name="priority" value="{{ old('priority', 0) }}" 
                               class="form-input @error('priority') error @enderror" min="0" required>
                        @error('priority')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status Aktif --}}
                    <div class="flex items-center sm:pt-7">
                        <input type="checkbox" id="is_active" name="is_active" value="1" checked
                               class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        <label for="is_active" class="ml-2 text-sm text-slate-700 font-medium">Aturan Aktif</label>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('evaluation-rules.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Aturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

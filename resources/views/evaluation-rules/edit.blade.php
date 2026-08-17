@extends('layouts.app')

@section('title', 'Ubah Aturan Evaluasi')
@section('page-title', 'Ubah Aturan Evaluasi')
@section('page-subtitle', 'Perbarui aturan penilaian yang digunakan sistem untuk menganalisis pertandingan')

@section('content')
<div class="max-w-3xl">
    <div class="card p-6">
        <form method="POST" action="{{ route('evaluation-rules.update', $rule->id) }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Nama Aturan --}}
                    <div>
                        <label for="rule_name" class="form-label">Nama Aturan <span class="text-rose-500">*</span></label>
                        <input type="text" id="rule_name" name="rule_name" value="{{ old('rule_name', $rule->rule_name) }}"
                               class="form-input @error('rule_name') error @enderror"
                               placeholder="Contoh: Sering Melakukan Error Sendiri" required>
                        @error('rule_name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Indikator --}}
                    <div>
                        <label for="indicator" class="form-label">Kategori Masalah <span class="text-rose-500">*</span></label>
                        <input type="text" id="indicator" name="indicator" value="{{ old('indicator', $rule->indicator) }}"
                               class="form-input @error('indicator') error @enderror"
                               placeholder="Contoh: Kelemahan Bertahan (Defence)" required>
                        @error('indicator')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Kapan Aturan Berlaku --}}
                <div>
                    <label for="condition_logic" class="form-label">Kapan Aturan Ini Berlaku? <span class="text-rose-500">*</span></label>
                    <input type="text" id="condition_logic" name="condition_logic" value="{{ old('condition_logic', $rule->condition_logic) }}"
                           class="form-input @error('condition_logic') error @enderror"
                           placeholder="Contoh: Jika pasangan lebih dari 30% melakukan kesalahan sendiri" required>
                    <p class="text-xs text-slate-400 mt-1">Tuliskan kondisi dalam kalimat yang mudah dipahami pelatih</p>
                    @error('condition_logic')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Parameter Teknis --}}
                <div class="p-4 bg-slate-50 border border-slate-100 rounded-lg space-y-4">
                    <div>
                        <span class="text-xs font-bold text-slate-500 block uppercase tracking-wider">⚙️ Pengaturan Teknis (untuk sistem)</span>
                        <p class="text-xs text-slate-400 mt-1">Bagian ini digunakan sistem untuk mencocokkan data secara otomatis.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        {{-- Param --}}
                        <div>
                            <label for="condition_param" class="form-label text-xs">Ukuran yang Dicek <span class="text-rose-500">*</span></label>
                            <select id="condition_param" name="condition_param" class="form-input text-xs" required>
                                @foreach($params as $key => $val)
                                    <option value="{{ $key }}" {{ old('condition_param', $rule->condition_param) === $key ? 'selected' : '' }}>
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
                                @foreach($operators as $key => $val)
                                    <option value="{{ $key }}" {{ old('condition_operator', $rule->condition_operator) === $key ? 'selected' : '' }}>
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
                            <input type="text" id="condition_value" name="condition_value" value="{{ old('condition_value', $rule->condition_value) }}"
                                   class="form-input text-xs" placeholder="Misal: 30 atau Defence atau true" required>
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
                              class="form-input @error('evaluation_result') error @enderror"
                              placeholder="Tuliskan catatan evaluasi dalam bahasa pelatih yang mudah dipahami..." required>{{ old('evaluation_result', $rule->evaluation_result) }}</textarea>
                    @error('evaluation_result')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kalimat Alasan --}}
                <div>
                    <label for="evaluation_reason" class="form-label">Kalimat Alasan Evaluasi <span class="text-rose-500">*</span></label>
                    <textarea id="evaluation_reason" name="evaluation_reason" rows="2"
                              class="form-input @error('evaluation_reason') error @enderror"
                              placeholder="Contoh: Dari total rally, [angka]% diakhiri dengan kesalahan sendiri." required>{{ old('evaluation_reason', $rule->evaluation_reason) }}</textarea>
                    <p class="text-xs text-slate-400 mt-1">💡 Tulis <code class='bg-slate-100 px-1 rounded text-slate-600'>[angka]</code> di posisi angka yang nanti akan terisi otomatis dari data pertandingan, dan <code class='bg-slate-100 px-1 rounded text-slate-600'>[nama_pemain]</code> di posisi yang akan terisi nama pemain secara otomatis.</p>
                    @error('evaluation_reason')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Priority --}}
                    <div>
                        <label for="priority" class="form-label">Prioritas Aturan <span class="text-rose-500">*</span></label>
                        <input type="number" id="priority" name="priority" value="{{ old('priority', $rule->priority) }}"
                               class="form-input @error('priority') error @enderror" min="0" required>
                        <p class="text-xs text-slate-400 mt-1">💡 Semakin kecil angkanya, semakin penting aturan ini (diperiksa lebih dulu oleh sistem)</p>
                        @error('priority')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status Aktif --}}
                    <div class="flex items-center sm:pt-7">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $rule->is_active) ? 'checked' : '' }}
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

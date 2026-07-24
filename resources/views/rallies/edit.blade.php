@extends('layouts.app')

@section('title', 'Ubah Data Rally')
@section('page-title', 'Ubah Data Rally')
@section('page-subtitle')
    Laga: {{ $match->pair->name ?? '-' }} vs {{ $match->opponent_name }} ({{ $match->match_date->format('d M Y') }})
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="card p-6">
        <h3 class="text-sm font-semibold text-slate-800 border-b border-slate-100 pb-3 mb-5">
            Ubah Rally #{{ $rally->rally_number }} — Set {{ $rally->set_number }}
        </h3>

        <form method="POST" action="{{ route('rallies.update', [$match->id, $rally->id]) }}" id="rally-edit-form">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    {{-- Set Number --}}
                    <div>
                        <label for="set_number" class="form-label">Set <span class="text-rose-500">*</span></label>
                        <select id="set_number" name="set_number" class="form-input" required>
                            <option value="1" {{ old('set_number', $rally->set_number) == 1 ? 'selected' : '' }}>Set 1</option>
                            <option value="2" {{ old('set_number', $rally->set_number) == 2 ? 'selected' : '' }}>Set 2</option>
                            <option value="3" {{ old('set_number', $rally->set_number) == 3 ? 'selected' : '' }}>Set 3</option>
                        </select>
                    </div>

                    {{-- Rally Number --}}
                    <div>
                        <label for="rally_number" class="form-label">Rally Ke- <span class="text-rose-500">*</span></label>
                        <input type="number" id="rally_number" name="rally_number" value="{{ old('rally_number', $rally->rally_number) }}"
                               class="form-input" min="1" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Pair Score --}}
                    <div>
                        <label for="pair_score" class="form-label">Skor Pasangan <span class="text-rose-500">*</span></label>
                        <input type="number" id="pair_score" name="pair_score" value="{{ old('pair_score', $rally->pair_score) }}"
                               class="form-input" min="0" required>
                    </div>

                    {{-- Opponent Score --}}
                    <div>
                        <label for="opponent_score" class="form-label">Skor Lawan <span class="text-rose-500">*</span></label>
                        <input type="number" id="opponent_score" name="opponent_score" value="{{ old('opponent_score', $rally->opponent_score) }}"
                               class="form-input" min="0" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Point Winner --}}
                    <div>
                        <label for="point_winner" class="form-label">Pemenang Poin <span class="text-rose-500">*</span></label>
                        <select id="point_winner" name="point_winner" class="form-input" onchange="suggestPointResult()" required>
                            <option value="Pasangan" {{ old('point_winner', $rally->point_winner) === 'Pasangan' ? 'selected' : '' }}>Pasangan</option>
                            <option value="Lawan" {{ old('point_winner', $rally->point_winner) === 'Lawan' ? 'selected' : '' }}>Lawan</option>
                        </select>
                    </div>

                    {{-- Point Result --}}
                    <div>
                        <label for="point_result" class="form-label">Hasil Poin <span class="text-rose-500">*</span></label>
                        <select id="point_result" name="point_result" class="form-input" onchange="toggleErrorFields()" required>
                            <option value="Winner" {{ old('point_result', $rally->point_result) === 'Winner' ? 'selected' : '' }}>Winner (Pukulan Masuk)</option>
                            <option value="Error Lawan" {{ old('point_result', $rally->point_result) === 'Error Lawan' ? 'selected' : '' }}>Error Lawan</option>
                            <option value="Error Sendiri" {{ old('point_result', $rally->point_result) === 'Error Sendiri' ? 'selected' : '' }}>Error Sendiri (Unforced Error)</option>
                        </select>
                    </div>
                </div>

                {{-- Error Fields --}}
                <div id="error-fields" class="space-y-4 p-4 bg-slate-50 border border-slate-100 rounded-lg">
                    <div>
                        <label for="error_type" class="form-label">Jenis Error <span class="text-rose-500">*</span></label>
                        <select id="error_type" name="error_type" class="form-input">
                            <option value="">-- Pilih Jenis Error --</option>
                            <optgroup label="🏸 Pukulan">
                                <option value="Serve" {{ old('error_type', $rally->error_type) === 'Serve' ? 'selected' : '' }}>Serve (Kesalahan servis)</option>
                                <option value="Smash" {{ old('error_type', $rally->error_type) === 'Smash' ? 'selected' : '' }}>Smash (Pukulan keras gagal)</option>
                                <option value="Drive" {{ old('error_type', $rally->error_type) === 'Drive' ? 'selected' : '' }}>Drive (Pukulan datar gagal)</option>
                                <option value="Lift" {{ old('error_type', $rally->error_type) === 'Lift' ? 'selected' : '' }}>Lift (Angkat bola gagal)</option>
                                <option value="Lob" {{ old('error_type', $rally->error_type) === 'Lob' ? 'selected' : '' }}>Lob (Pukulan melambung gagal)</option>
                                <option value="Drop Shot" {{ old('error_type', $rally->error_type) === 'Drop Shot' ? 'selected' : '' }}>Drop Shot (Nembak pendek gagal)</option>
                                <option value="Netting" {{ old('error_type', $rally->error_type) === 'Netting' ? 'selected' : '' }}>Netting (Tipis net gagal)</option>
                                <option value="Defence" {{ old('error_type', $rally->error_type) === 'Defence' ? 'selected' : '' }}>Defence (Bertahan gagal)</option>
                            </optgroup>
                            <optgroup label="⚡ Teknik & Posisi">
                                <option value="Net" {{ old('error_type', $rally->error_type) === 'Net' ? 'selected' : '' }}>Net (Bola menyangkut net)</option>
                                <option value="Out" {{ old('error_type', $rally->error_type) === 'Out' ? 'selected' : '' }}>Out (Keluar lapangan)</option>
                                <option value="Timing" {{ old('error_type', $rally->error_type) === 'Timing' ? 'selected' : '' }}>Timing (Salah waktu pukulan)</option>
                                <option value="Footwork" {{ old('error_type', $rally->error_type) === 'Footwork' ? 'selected' : '' }}>Footwork (Salah posisi kaki)</option>
                            </optgroup>
                            <optgroup label="👥 Koordinasi">
                                <option value="Miskomunikasi" {{ old('error_type', $rally->error_type) === 'Miskomunikasi' ? 'selected' : '' }}>Miskomunikasi (Salah paham)</option>
                            </optgroup>
                            <optgroup label="🌦️ Faktor Non-Teknis">
                                <option value="Angin lapangan" {{ old('error_type', $rally->error_type) === 'Angin lapangan' ? 'selected' : '' }}>Angin lapangan</option>
                                <option value="Lantai licin" {{ old('error_type', $rally->error_type) === 'Lantai licin' ? 'selected' : '' }}>Lantai licin</option>
                                <option value="Cahaya silau/redup" {{ old('error_type', $rally->error_type) === 'Cahaya silau/redup' ? 'selected' : '' }}>Cahaya silau/redup</option>
                                <option value="Raket patah" {{ old('error_type', $rally->error_type) === 'Raket patah' ? 'selected' : '' }}>Raket patah</option>
                                <option value="Senar putus" {{ old('error_type', $rally->error_type) === 'Senar putus' ? 'selected' : '' }}>Senar putus</option>
                                <option value="Shuttlecock rusak" {{ old('error_type', $rally->error_type) === 'Shuttlecock rusak' ? 'selected' : '' }}>Shuttlecock rusak</option>
                                <option value="Human error (wasit)" {{ old('error_type', $rally->error_type) === 'Human error (wasit)' ? 'selected' : '' }}>Human error (wasit)</option>
                            </optgroup>
                        </select>
                        @error('error_type')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="error_player_id" class="form-label">Pemain yang Melakukan Error <span class="text-rose-500">*</span></label>
                        <select id="error_player_id" name="error_player_id" class="form-input">
                            <option value="">-- Pilih Pemain --</option>
                            @if($match->pair->player1)
                                <option value="{{ $match->pair->player1->id }}" {{ old('error_player_id', $rally->error_player_id) == $match->pair->player1->id ? 'selected' : '' }}>
                                    {{ $match->pair->player1->name }}
                                </option>
                            @endif
                            @if($match->pair->player2)
                                <option value="{{ $match->pair->player2->id }}" {{ old('error_player_id', $rally->error_player_id) == $match->pair->player2->id ? 'selected' : '' }}>
                                    {{ $match->pair->player2->name }}
                                </option>
                            @endif
                        </select>
                        @error('error_player_id')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Stroke Count --}}
                    <div>
                        <label for="stroke_count" class="form-label">Jumlah Pukulan</label>
                        <input type="number" id="stroke_count" name="stroke_count" value="{{ old('stroke_count', $rally->stroke_count) }}"
                               class="form-input" min="1">
                    </div>

                    {{-- Rally Duration --}}
                    <div>
                        <label for="rally_duration" class="form-label">Durasi Rally (detik)</label>
                        <input type="number" id="rally_duration" name="rally_duration" value="{{ old('rally_duration', $rally->rally_duration) }}"
                               class="form-input" min="1">
                    </div>
                </div>

                {{-- Critical Point Checkbox --}}
                <div class="flex items-center">
                    <input type="checkbox" id="is_critical_point" name="is_critical_point" value="1"
                           {{ old('is_critical_point', $rally->is_critical_point) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                    <label for="is_critical_point" class="ml-2 text-sm text-slate-700 font-medium">Poin Kritis (Menegangkan / Penentu Set)</label>
                </div>

                {{-- Remarks --}}
                <div>
                    <label for="remarks" class="form-label">Keterangan Tambahan</label>
                    <textarea id="remarks" name="remarks" rows="2" class="form-input">{{ old('remarks', $rally->remarks) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('rallies.index', $match->id) }}" class="btn btn-outline">Batal</a>
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

@push('scripts')
<script>
    function toggleErrorFields() {
        const resultSelect = document.getElementById('point_result');
        const errorFields = document.getElementById('error-fields');
        const errorType = document.getElementById('error_type');
        const errorPlayer = document.getElementById('error_player_id');

        if (resultSelect.value === 'Error Sendiri') {
            errorFields.classList.remove('hidden');
            errorType.required = true;
            errorPlayer.required = true;
        } else {
            errorFields.classList.add('hidden');
            errorType.required = false;
            errorPlayer.required = false;
        }
    }

    function suggestPointResult() {
        const winner = document.getElementById('point_winner').value;
        const resultSelect = document.getElementById('point_result');

        if (winner === 'Pasangan') {
            resultSelect.value = 'Winner';
        } else if (winner === 'Lawan') {
            resultSelect.value = 'Error Sendiri';
        }
        toggleErrorFields();
    }

    // Run on load to properly show/hide error fields
    document.addEventListener('DOMContentLoaded', () => {
        toggleErrorFields();
    });
</script>
@endpush

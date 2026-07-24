@extends('layouts.app')

@section('title', 'Kelola Rally')
@section('page-title', 'Kelola Rally')
@section('page-subtitle')
    Laga: {{ $match->pair->name ?? '-' }} vs {{ $match->opponent_name }} ({{ $match->match_date->format('d M Y') }})
@endsection

@section('content')
{{-- Top Section: Full Width Video Player --}}
<div class="w-full mb-6">
    {{-- Video Preview Panel --}}
    <div class="card p-5">
        <h3 class="text-sm font-semibold text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Preview Video Pertandingan
        </h3>

        {{-- Upload area --}}
        <div id="video-upload-area" class="relative">
            <label for="video-file" class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-slate-200 rounded-xl cursor-pointer hover:border-primary-400 hover:bg-primary-50/30 transition-all group aspect-video" style="max-height: 400px;">
                <div class="w-12 h-12 rounded-full bg-slate-100 group-hover:bg-primary-100 flex items-center justify-center mb-3 transition-colors">
                    <svg class="w-6 h-6 text-slate-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <p class="text-xs font-semibold text-slate-600 group-hover:text-primary-700">Pilih atau seret video ke sini</p>
                <p class="text-[10px] text-slate-400 mt-1">MP4, WEBM, AVI, MOV (Maks. 3GB)</p>
            </label>
            <input type="file" id="video-file" accept="video/mp4,video/webm,video/x-msvideo,video/quicktime,video/*" class="hidden">
        </div>

        {{-- Video Player (hidden until video loaded) --}}
        <div id="video-player-container" class="hidden">
            {{-- Video element --}}
            <div class="relative w-full aspect-video rounded-xl overflow-hidden bg-black flex items-center justify-center border border-slate-800" style="max-height: 480px;">
                <video id="video-player" class="w-full h-full object-contain" controls preload="metadata">
                    Browser Anda tidak mendukung pemutar video.
                </video>
            </div>

            {{-- Video info bar --}}
            <div id="video-info" class="mt-2 px-1 flex items-center justify-between text-[10px] text-slate-400">
                <span id="video-filename" class="truncate max-w-[300px] font-medium text-slate-500"></span>
                <span id="video-duration" class="bg-slate-100 px-2 py-0.5 rounded text-slate-600 font-semibold"></span>
            </div>

            {{-- Custom controls --}}
            <div class="mt-3 space-y-2 bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                {{-- Playback speed --}}
                <div class="flex items-center gap-2">
                    <span class="text-[10px] text-slate-500 font-semibold w-14">Kecepatan:</span>
                    <div class="flex gap-1 flex-wrap">
                        <button type="button" onclick="setPlaybackRate(0.25)" class="speed-btn text-[9px] px-2 py-0.5 rounded bg-white border border-slate-200 hover:bg-primary-50 hover:text-primary-600 transition-colors" data-speed="0.25">0.25x</button>
                        <button type="button" onclick="setPlaybackRate(0.5)" class="speed-btn text-[9px] px-2 py-0.5 rounded bg-white border border-slate-200 hover:bg-primary-50 hover:text-primary-600 transition-colors" data-speed="0.5">0.5x</button>
                        <button type="button" onclick="setPlaybackRate(1)" class="speed-btn active text-[9px] px-2 py-0.5 rounded bg-primary-600 text-white font-semibold shadow-sm transition-colors" data-speed="1">1.0x</button>
                        <button type="button" onclick="setPlaybackRate(1.5)" class="speed-btn text-[9px] px-2 py-0.5 rounded bg-white border border-slate-200 hover:bg-primary-50 hover:text-primary-600 transition-colors" data-speed="1.5">1.5x</button>
                        <button type="button" onclick="setPlaybackRate(2)" class="speed-btn text-[9px] px-2 py-0.5 rounded bg-white border border-slate-200 hover:bg-primary-50 hover:text-primary-600 transition-colors" data-speed="2">2.0x</button>
                    </div>
                </div>

                {{-- Frame controls --}}
                <div class="flex items-center gap-2">
                    <span class="text-[10px] text-slate-500 font-semibold w-14">Navigasi:</span>
                    <div class="flex gap-1 flex-wrap">
                        <button type="button" onclick="skipVideo(-5)" class="text-[9px] px-2 py-0.5 rounded bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 transition-colors" title="Mundur 5 detik">◀ 5s</button>
                        <button type="button" onclick="frameStep(-1)" class="text-[9px] px-2 py-0.5 rounded bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 transition-colors" title="Mundur 1 frame">◁ Fr</button>
                        <button type="button" onclick="frameStep(1)" class="text-[9px] px-2 py-0.5 rounded bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 transition-colors" title="Maju 1 frame">Fr ▷</button>
                        <button type="button" onclick="skipVideo(5)" class="text-[9px] px-2 py-0.5 rounded bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 transition-colors" title="Maju 5 detik">5s ▶</button>
                    </div>
                </div>
            </div>

            {{-- Change / Remove video --}}
            <div class="mt-3 flex gap-2">
                <button type="button" onclick="document.getElementById('video-file').click()" class="btn btn-outline text-[10px] flex-1 justify-center py-1.5">
                    Ganti Video
                </button>
                <button type="button" onclick="removeVideo()" class="btn text-[10px] flex-1 justify-center py-1.5 bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Bottom Section: Split Columns --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Form Input Rally --}}
    <div class="lg:col-span-1 space-y-4">
        <div class="card p-5">
            <h3 class="text-sm font-semibold text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Input Data Rally
            </h3>

            <form method="POST" action="{{ route('rallies.store', $match->id) }}" id="rally-form">
                @csrf

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        {{-- Set Number --}}
                        <div>
                            <label for="set_number" class="form-label text-xs">Set <span class="text-rose-500">*</span></label>
                            <select id="set_number" name="set_number" class="form-input text-xs" onchange="autoSuggestRallyNumber()" required>
                                <option value="1" {{ old('set_number') == 1 ? 'selected' : '' }}>Set 1</option>
                                <option value="2" {{ old('set_number') == 2 ? 'selected' : '' }}>Set 2</option>
                                <option value="3" {{ old('set_number') == 3 ? 'selected' : '' }}>Set 3</option>
                            </select>
                        </div>

                        {{-- Rally Number --}}
                        <div>
                            <label for="rally_number" class="form-label text-xs">Rally Ke- <span class="text-rose-500">*</span></label>
                            <input type="number" id="rally_number" name="rally_number" value="{{ old('rally_number') }}"
                                   class="form-input text-xs" min="1" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Pair Score --}}
                        <div>
                            <label for="pair_score" class="form-label text-xs">Skor Pasangan <span class="text-rose-500">*</span></label>
                            <input type="number" id="pair_score" name="pair_score" value="{{ old('pair_score') }}"
                                   class="form-input text-xs" min="0" oninput="checkCriticalPoint()" required>
                        </div>

                        {{-- Opponent Score --}}
                        <div>
                            <label for="opponent_score" class="form-label text-xs">Skor Lawan <span class="text-rose-500">*</span></label>
                            <input type="number" id="opponent_score" name="opponent_score" value="{{ old('opponent_score') }}"
                                   class="form-input text-xs" min="0" oninput="checkCriticalPoint()" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Point Winner --}}
                        <div>
                            <label for="point_winner" class="form-label text-xs">Pemenang Poin <span class="text-rose-500">*</span></label>
                            <select id="point_winner" name="point_winner" class="form-input text-xs" onchange="suggestPointResult()" required>
                                <option value="">-- Pilih --</option>
                                <option value="Pasangan" {{ old('point_winner') === 'Pasangan' ? 'selected' : '' }}>Pasangan</option>
                                <option value="Lawan" {{ old('point_winner') === 'Lawan' ? 'selected' : '' }}>Lawan</option>
                            </select>
                        </div>

                        {{-- Point Result --}}
                        <div>
                            <label for="point_result" class="form-label text-xs">Hasil Poin <span class="text-rose-500">*</span></label>
                            <select id="point_result" name="point_result" class="form-input text-xs" onchange="toggleErrorFields()" required>
                                <option value="">-- Pilih --</option>
                                <option value="Winner" {{ old('point_result') === 'Winner' ? 'selected' : '' }}>Winner (Pukulan Masuk)</option>
                                <option value="Error Lawan" {{ old('point_result') === 'Error Lawan' ? 'selected' : '' }}>Error Lawan</option>
                                <option value="Error Sendiri" {{ old('point_result') === 'Error Sendiri' ? 'selected' : '' }}>Error Sendiri (Unforced Error)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Error Fields (Visible only when point_result == 'Error Sendiri') --}}
                    <div id="error-fields" class="hidden space-y-3 p-3 bg-slate-50 border border-slate-100 rounded-lg">
                        <div>
                            <label for="error_type" class="form-label text-xs">Jenis Error <span class="text-rose-500">*</span></label>
                            <select id="error_type" name="error_type" class="form-input text-xs">
                                <option value="">-- Pilih Jenis Error --</option>
                                <optgroup label="🏸 Pukulan">
                                    <option value="Serve" {{ old('error_type') === 'Serve' ? 'selected' : '' }}>Serve (Kesalahan servis)</option>
                                    <option value="Smash" {{ old('error_type') === 'Smash' ? 'selected' : '' }}>Smash (Pukulan keras gagal)</option>
                                    <option value="Drive" {{ old('error_type') === 'Drive' ? 'selected' : '' }}>Drive (Pukulan datar gagal)</option>
                                    <option value="Lift" {{ old('error_type') === 'Lift' ? 'selected' : '' }}>Lift (Angkat bola gagal)</option>
                                    <option value="Lob" {{ old('error_type') === 'Lob' ? 'selected' : '' }}>Lob (Pukulan melambung gagal)</option>
                                    <option value="Drop Shot" {{ old('error_type') === 'Drop Shot' ? 'selected' : '' }}>Drop Shot (Nembak pendek gagal)</option>
                                    <option value="Netting" {{ old('error_type') === 'Netting' ? 'selected' : '' }}>Netting (Tipis net gagal)</option>
                                    <option value="Defence" {{ old('error_type') === 'Defence' ? 'selected' : '' }}>Defence (Bertahan gagal)</option>
                                </optgroup>
                                <optgroup label="⚡ Teknik & Posisi">
                                    <option value="Net" {{ old('error_type') === 'Net' ? 'selected' : '' }}>Net (Bola menyangkut net)</option>
                                    <option value="Out" {{ old('error_type') === 'Out' ? 'selected' : '' }}>Out (Keluar lapangan)</option>
                                    <option value="Timing" {{ old('error_type') === 'Timing' ? 'selected' : '' }}>Timing (Salah waktu pukulan)</option>
                                    <option value="Footwork" {{ old('error_type') === 'Footwork' ? 'selected' : '' }}>Footwork (Salah posisi kaki)</option>
                                </optgroup>
                                <optgroup label="👥 Koordinasi">
                                    <option value="Miskomunikasi" {{ old('error_type') === 'Miskomunikasi' ? 'selected' : '' }}>Miskomunikasi (Salah paham)</option>
                                </optgroup>
                                <optgroup label="🌦️ Faktor Non-Teknis">
                                    <option value="Angin lapangan" {{ old('error_type') === 'Angin lapangan' ? 'selected' : '' }}>Angin lapangan</option>
                                    <option value="Lantai licin" {{ old('error_type') === 'Lantai licin' ? 'selected' : '' }}>Lantai licin</option>
                                    <option value="Cahaya silau/redup" {{ old('error_type') === 'Cahaya silau/redup' ? 'selected' : '' }}>Cahaya silau/redup</option>
                                    <option value="Raket patah" {{ old('error_type') === 'Raket patah' ? 'selected' : '' }}>Raket patah</option>
                                    <option value="Senar putus" {{ old('error_type') === 'Senar putus' ? 'selected' : '' }}>Senar putus</option>
                                    <option value="Shuttlecock rusak" {{ old('error_type') === 'Shuttlecock rusak' ? 'selected' : '' }}>Shuttlecock rusak</option>
                                    <option value="Human error (wasit)" {{ old('error_type') === 'Human error (wasit)' ? 'selected' : '' }}>Human error (wasit)</option>
                                </optgroup>
                            </select>
                            @error('error_type')
                                <p class="form-error text-[10px]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="error_player_id" class="form-label text-xs">Pemain yang Melakukan Error <span class="text-rose-500">*</span></label>
                            <select id="error_player_id" name="error_player_id" class="form-input text-xs">
                                <option value="">-- Pilih Pemain --</option>
                                @if($match->pair->player1)
                                    <option value="{{ $match->pair->player1->id }}" {{ old('error_player_id') == $match->pair->player1->id ? 'selected' : '' }}>
                                        {{ $match->pair->player1->name }}
                                    </option>
                                @endif
                                @if($match->pair->player2)
                                    <option value="{{ $match->pair->player2->id }}" {{ old('error_player_id') == $match->pair->player2->id ? 'selected' : '' }}>
                                        {{ $match->pair->player2->name }}
                                    </option>
                                @endif
                            </select>
                            @error('error_player_id')
                                <p class="form-error text-[10px]">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Stroke Count --}}
                        <div>
                            <label for="stroke_count" class="form-label text-xs">Jumlah Pukulan</label>
                            <input type="number" id="stroke_count" name="stroke_count" value="{{ old('stroke_count') }}"
                                   class="form-input text-xs" min="1" placeholder="Pukulan">
                        </div>

                        {{-- Rally Duration --}}
                        <div>
                            <label for="rally_duration" class="form-label text-xs">Durasi Rally (detik)</label>
                            <input type="number" id="rally_duration" name="rally_duration" value="{{ old('rally_duration') }}"
                                   class="form-input text-xs" min="1" placeholder="Detik">
                        </div>
                    </div>

                    {{-- Critical Point Checkbox --}}
                    <div class="flex items-center">
                        <input type="checkbox" id="is_critical_point" name="is_critical_point" value="1"
                               {{ old('is_critical_point') ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        <label for="is_critical_point" class="ml-2 text-xs text-slate-700 font-medium">Poin Kritis (Menegangkan / Penentu Set)</label>
                    </div>

                    {{-- Remarks --}}
                    <div>
                        <label for="remarks" class="form-label text-xs">Keterangan Tambahan</label>
                        <textarea id="remarks" name="remarks" rows="2" class="form-input text-xs" placeholder="Misal: flick serve lawan, drive lurus...">{{ old('remarks') }}</textarea>
                    </div>
                </div>

                <div class="mt-5 flex gap-2">
                    <button type="button" onclick="resetForm()" class="btn btn-outline flex-1 justify-center text-xs">Reset</button>
                    <button type="submit" class="btn btn-primary flex-1 justify-center text-xs">Tambah Rally</button>
                </div>
            </form>
        </div>
    </div>

    {{-- List Rallies Column --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
                <h3 class="text-sm font-semibold text-slate-800">Riwayat Input Rally</h3>
                <span class="badge badge-final text-xs">Total: {{ $match->rallies->count() }} Rally</span>
            </div>
            <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                <table class="data-table responsive-cards text-xs">
                    <thead class="sticky top-0 z-10 bg-white">
                        <tr>
                            <th class="w-12">Set</th>
                            <th class="w-12">Rally</th>
                            <th class="text-center w-16">Skor</th>
                            <th>Pemenang</th>
                            <th>Hasil</th>
                            <th>Penyebab Error</th>
                            <th>Pemain Error</th>
                            <th class="w-16">Pukulan</th>
                            <th class="w-16">Durasi</th>
                            <th class="w-12 text-center">Kritis</th>
                            <th class="w-24 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($match->rallies->sortBy([['set_number', 'desc'], ['rally_number', 'desc']]) as $rally)
                            <tr>
                                <td data-label="Set">Set {{ $rally->set_number }}</td>
                                <td data-label="Rally">#{{ $rally->rally_number }}</td>
                                <td data-label="Skor" class="text-center font-bold">
                                    <span class="text-primary-600">{{ $rally->pair_score }}</span> - <span class="text-slate-500">{{ $rally->opponent_score }}</span>
                                </td>
                                <td data-label="Pemenang">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $rally->point_winner === 'Pasangan' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $rally->point_winner }}
                                    </span>
                                </td>
                                <td data-label="Hasil">{{ $rally->point_result }}</td>
                                <td data-label="Penyebab Error">{{ $rally->error_type ?? '-' }}</td>
                                <td data-label="Pemain Error">{{ $rally->errorPlayer->name ?? '-' }}</td>
                                <td data-label="Pukulan">{{ $rally->stroke_count ? $rally->stroke_count . ' pukulan' : '-' }}</td>
                                <td data-label="Durasi">{{ $rally->rally_duration ? $rally->rally_duration . ' dtk' : '-' }}</td>
                                <td data-label="Kritis" class="text-center">
                                    @if($rally->is_critical_point)
                                        <span class="text-rose-500 font-bold">Ya</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td data-label="Aksi" class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('rallies.edit', [$match->id, $rally->id]) }}" class="p-1 text-slate-500 hover:text-amber-600 transition-colors" title="Ubah">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <button onclick="confirmDelete('{{ route('rallies.destroy', [$match->id, $rally->id]) }}', 'Hapus rally #{{ $rally->rally_number }} di Set {{ $rally->set_number }}?')" class="p-1 text-slate-500 hover:text-rose-600 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-12 text-slate-400">Belum ada data rally. Gunakan form di sebelah kiri untuk menambah data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Bottom Actions --}}
        <div class="flex items-center justify-between flex-wrap gap-3">
            <a href="{{ route('matches.show', $match->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs border border-slate-300 transition-all">
                Kembali ke Detail Match
            </a>
            @if($match->rallies->count() > 0)
                <a href="{{ route('verification.show', $match->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/30 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Verifikasi & Finalisasi Data
                </a>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Store rallies in JS to calculate next suggestion
    const rallies = @json($match->rallies);

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
            errorType.value = '';
            errorPlayer.value = '';
        }
    }

    function suggestPointResult() {
        const winner = document.getElementById('point_winner').value;
        const resultSelect = document.getElementById('point_result');

        if (winner === 'Pasangan') {
            // Pasangan get point, either Winner or Opponent error
            resultSelect.value = 'Winner';
        } else if (winner === 'Lawan') {
            // Lawan get point, typically due to our error
            resultSelect.value = 'Error Sendiri';
        } else {
            resultSelect.value = '';
        }

        toggleErrorFields();
    }

    function checkCriticalPoint() {
        const pairScore = parseInt(document.getElementById('pair_score').value) || 0;
        const oppScore = parseInt(document.getElementById('opponent_score').value) || 0;
        const criticalCheckbox = document.getElementById('is_critical_point');

        // Automatically suggest critical point if score >= 18 for either side
        if (pairScore >= 18 || oppScore >= 18) {
            criticalCheckbox.checked = true;
        } else {
            criticalCheckbox.checked = false;
        }
    }

    function autoSuggestRallyNumber() {
        const setSelect = document.getElementById('set_number');
        const setNum = parseInt(setSelect.value);

        // Remember current selected set in localStorage
        try {
            localStorage.setItem('selected_set_' + {{ $match->id }}, setNum);
        } catch(e) {}

        const setRallies = rallies.filter(r => r.set_number === setNum);

        let nextRally = 1;
        let lastPairScore = 0;
        let lastOppScore = 0;

        if (setRallies.length > 0) {
            // Sort by rally_number descending to find the last one
            setRallies.sort((a, b) => b.rally_number - a.rally_number);
            const lastRallyObj = setRallies[0];

            nextRally = lastRallyObj.rally_number + 1;
            lastPairScore = lastRallyObj.pair_score;
            lastOppScore = lastRallyObj.opponent_score;
        }

        document.getElementById('rally_number').value = nextRally;
        document.getElementById('pair_score').value = lastPairScore;
        document.getElementById('opponent_score').value = lastOppScore;
        
        // Check critical status based on suggested scores
        checkCriticalPoint();

        // Default inputs
        document.getElementById('point_winner').value = '';
        document.getElementById('point_result').value = '';
        document.getElementById('stroke_count').value = '';
        document.getElementById('rally_duration').value = '';
        const remarksInput = document.getElementById('remarks');
        if (remarksInput) remarksInput.value = '';

        toggleErrorFields();
    }

    function resetForm() {
        document.getElementById('rally-form').reset();
        autoSuggestRallyNumber();
    }

    const playersMap = {
        @if($match->pair && $match->pair->player1)
            {{ $match->pair->player1->id }}: '{{ addslashes($match->pair->player1->name) }}',
        @endif
        @if($match->pair && $match->pair->player2)
            {{ $match->pair->player2->id }}: '{{ addslashes($match->pair->player2->name) }}',
        @endif
    };

    function getPlayerNameById(id) {
        return playersMap[id] || '-';
    }

    function renderRalliesTable(ralliesList) {
        const tbody = document.querySelector('.data-table tbody');
        const totalBadge = document.querySelector('.badge-final');
        if (totalBadge) {
            totalBadge.textContent = `Total: ${ralliesList.length} Rally`;
        }

        if (!ralliesList || ralliesList.length === 0) {
            tbody.innerHTML = `<tr><td colspan="11" class="text-center py-12 text-slate-400">Belum ada data rally. Gunakan form di sebelah kiri untuk menambah data.</td></tr>`;
            return;
        }

        const sorted = [...ralliesList].sort((a, b) => {
            if (b.set_number !== a.set_number) return b.set_number - a.set_number;
            return b.rally_number - a.rally_number;
        });

        tbody.innerHTML = sorted.map(r => `
            <tr>
                <td data-label="Set">Set ${r.set_number}</td>
                <td data-label="Rally">#${r.rally_number}</td>
                <td data-label="Skor" class="text-center font-bold">
                    <span class="text-primary-600">${r.pair_score}</span> - <span class="text-slate-500">${r.opponent_score}</span>
                </td>
                <td data-label="Pemenang">
                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold ${r.point_winner === 'Pasangan' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'}">
                        ${r.point_winner}
                    </span>
                </td>
                <td data-label="Hasil">${r.point_result}</td>
                <td data-label="Penyebab Error">${r.error_type || '-'}</td>
                <td data-label="Pemain Error">${r.error_player ? r.error_player.name : (r.error_player_id ? getPlayerNameById(r.error_player_id) : '-')}</td>
                <td data-label="Pukulan">${r.stroke_count ? r.stroke_count + ' pukulan' : '-'}</td>
                <td data-label="Durasi">${r.rally_duration ? r.rally_duration + ' dtk' : '-'}</td>
                <td data-label="Kritis" class="text-center">
                    ${r.is_critical_point ? '<span class="text-rose-500 font-bold">Ya</span>' : '<span class="text-slate-300">-</span>'}
                </td>
                <td data-label="Aksi" class="text-center">
                    <div class="flex items-center justify-center gap-1">
                        <a href="/matches/${matchId}/rallies/${r.id}/edit" class="p-1 text-slate-500 hover:text-amber-600 transition-colors" title="Ubah">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <button onclick="confirmDelete('/matches/${matchId}/rallies/${r.id}', 'Hapus rally #${r.rally_number} di Set ${r.set_number}?')" class="p-1 text-slate-500 hover:text-rose-600 transition-colors" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function showToastSuccess(msg) {
        let toast = document.getElementById('toast-success');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'toast-success';
            toast.className = 'toast bg-emerald-500 text-white flex items-center gap-3';
            document.body.appendChild(toast);
        }
        toast.innerHTML = `
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>${msg}</span>
        `;
        toast.style.display = 'flex';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3500);
    }

    // Run suggestion & form interceptor on load
    document.addEventListener('DOMContentLoaded', () => {
        const setSelect = document.getElementById('set_number');
        @if(!old('set_number'))
            try {
                const savedSet = localStorage.getItem('selected_set_' + {{ $match->id }});
                if (savedSet && ['1', '2', '3'].includes(savedSet)) {
                    setSelect.value = savedSet;
                }
            } catch(e) {}
        @endif

        autoSuggestRallyNumber();
        initVideoPreview();

        // Handle AJAX Form Submit to prevent page refresh and keep video playing
        const rallyForm = document.getElementById('rally-form');
        if (rallyForm) {
            rallyForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');

                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = 'Menyimpan...';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        // Update JS rallies memory array
                        rallies.length = 0;
                        rallies.push(...data.rallies);

                        // Render updated table dynamically
                        renderRalliesTable(data.rallies);

                        // Show success Toast
                        showToastSuccess(data.message || 'Rally berhasil ditambahkan.');

                        // Auto suggest next rally number & scores
                        autoSuggestRallyNumber();
                    } else {
                        alert(data.message || 'Terjadi kesalahan saat menyimpan data rally.');
                    }
                } catch(err) {
                    console.error('AJAX Submit Error:', err);
                    form.submit(); // fallback to normal submit if fetch fails
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }
    });

    // ===== VIDEO PREVIEW & INDEXEDDB PERSISTENCE =====
    let videoObjectUrl = null;
    const dbName = 'BadmintonVideoDB_v4';
    const storeName = 'videos';
    const matchId = {{ $match->id }};

    function openVideoDB() {
        return new Promise((resolve) => {
            const request = indexedDB.open(dbName, 1);
            request.onupgradeneeded = function(e) {
                const db = e.target.result;
                if (!db.objectStoreNames.contains(storeName)) {
                    db.createObjectStore(storeName);
                }
            };
            request.onsuccess = function(e) {
                resolve(e.target.result);
            };
            request.onerror = function(e) {
                console.warn('IndexedDB open error:', e);
                resolve(null);
            };
        });
    }

    async function saveVideoToDB(file) {
        // Skip storing files > 300MB in IndexedDB to prevent browser quota crashes
        if (!file || file.size > 300 * 1024 * 1024) return;

        try {
            const db = await openVideoDB();
            if (!db) return;
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            store.put(file, 'match_' + matchId);
        } catch(e) {
            console.warn('Gagal menyimpan video ke IndexedDB:', e);
        }
    }

    async function loadSavedVideoFromDB() {
        try {
            const db = await openVideoDB();
            if (!db) return null;
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const req = store.get('match_' + matchId);
            return new Promise((resolve) => {
                req.onsuccess = function() {
                    resolve(req.result);
                };
                req.onerror = function() {
                    resolve(null);
                };
            });
        } catch(e) {
            return null;
        }
    }

    async function deleteVideoFromDB() {
        try {
            const db = await openVideoDB();
            if (!db) return;
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            store.delete('match_' + matchId);
        } catch(e) {}
    }

    async function initVideoPreview() {
        const fileInput = document.getElementById('video-file');
        const uploadArea = document.getElementById('video-upload-area');
        const label = uploadArea.querySelector('label');

        // Restore saved video from IndexedDB if available
        try {
            const savedData = await loadSavedVideoFromDB();
            if (savedData && (savedData instanceof Blob || savedData instanceof File) && savedData.size > 0) {
                loadVideo(savedData, true);
            }
        } catch(err) {
            console.warn('Gagal memuat ulang video dari IndexedDB:', err);
        }

        // File input change
        fileInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                loadVideo(e.target.files[0]);
            }
        });

        // Drag & Drop
        label.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('border-primary-400', 'bg-primary-50/50');
        });

        label.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('border-primary-400', 'bg-primary-50/50');
        });

        label.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('border-primary-400', 'bg-primary-50/50');

            const files = e.dataTransfer.files;
            if (files && files[0] && files[0].type.startsWith('video/')) {
                loadVideo(files[0]);
            } else {
                alert('Harap pilih file video yang valid (MP4, WEBM, AVI, MOV).');
            }
        });
    }

    function loadVideo(file, isRestoring = false) {
        if (!file) return;

        // Validate size (3GB)
        if (file.size > 3000 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 3GB.');
            return;
        }

        const player = document.getElementById('video-player');
        const uploadArea = document.getElementById('video-upload-area');
        const playerContainer = document.getElementById('video-player-container');
        const filenameSpan = document.getElementById('video-filename');
        const durationSpan = document.getElementById('video-duration');

        // Revoke previous object URL
        if (videoObjectUrl && !isRestoring) {
            URL.revokeObjectURL(videoObjectUrl);
        }

        // Create object URL & show player INSTANTLY
        videoObjectUrl = URL.createObjectURL(file);
        player.src = videoObjectUrl;

        // Set filename
        filenameSpan.textContent = file.name || 'Video Pertandingan';

        // Show player, hide upload INSTANTLY
        uploadArea.classList.add('hidden');
        playerContainer.classList.remove('hidden');

        // Record video timestamp on playback
        player.ontimeupdate = function() {
            if (player.currentTime > 0) {
                try {
                    localStorage.setItem('video_time_' + matchId, player.currentTime);
                } catch(e) {}
            }
        };

        // Duration on load & restore time position
        player.onloadedmetadata = function() {
            const mins = Math.floor(player.duration / 60);
            const secs = Math.floor(player.duration % 60).toString().padStart(2, '0');
            durationSpan.textContent = `${mins}:${secs}`;

            // Restore timestamp position if saved
            try {
                const savedTime = parseFloat(localStorage.getItem('video_time_' + matchId));
                if (savedTime && savedTime > 0 && savedTime < player.duration) {
                    player.currentTime = savedTime;
                }
            } catch(e) {}
        };

        // Save to IndexedDB in background
        if (!isRestoring) {
            saveVideoToDB(file);
        }
    }

    function removeVideo() {
        const player = document.getElementById('video-player');
        const uploadArea = document.getElementById('video-upload-area');
        const playerContainer = document.getElementById('video-player-container');
        const fileInput = document.getElementById('video-file');

        player.pause();
        player.src = '';

        if (videoObjectUrl) {
            URL.revokeObjectURL(videoObjectUrl);
            videoObjectUrl = null;
        }

        fileInput.value = '';
        deleteVideoFromDB();
        try {
            localStorage.removeItem('video_time_' + matchId);
        } catch(e) {}

        // Show upload, hide player
        playerContainer.classList.add('hidden');
        uploadArea.classList.remove('hidden');
    }

    function setPlaybackRate(rate) {
        const player = document.getElementById('video-player');
        player.playbackRate = rate;

        // Update button styles
        document.querySelectorAll('.speed-btn').forEach(btn => {
            if (parseFloat(btn.dataset.speed) === rate) {
                btn.classList.remove('bg-slate-100', 'text-slate-600');
                btn.classList.add('bg-primary-100', 'text-primary-700', 'font-semibold');
            } else {
                btn.classList.remove('bg-primary-100', 'text-primary-700', 'font-semibold');
                btn.classList.add('bg-slate-100', 'text-slate-600');
            }
        });
    }

    function skipVideo(seconds) {
        const player = document.getElementById('video-player');
        player.currentTime = Math.max(0, Math.min(player.duration, player.currentTime + seconds));
    }

    function frameStep(direction) {
        const player = document.getElementById('video-player');
        player.pause();
        // Approximate frame step: 1/30 second (30fps)
        const frameTime = 1 / 30;
        player.currentTime = Math.max(0, Math.min(player.duration, player.currentTime + (frameTime * direction)));
    }
</script>
@endpush

@extends('layouts.app')

@section('title', 'Hasil Evaluasi Pertandingan')
@section('page-title', 'Hasil Evaluasi Pertandingan')
@section('page-subtitle')
    Laga: {{ $match->pair->name ?? '-' }} vs {{ $match->opponent_name }} ({{ $match->match_date->format('d M Y') }})
@endsection

@section('content')
<div class="space-y-6">
    {{-- Match Info Summary --}}
    <div class="card p-5 text-white border-none relative overflow-hidden" style="background: linear-gradient(to right, #4f46e5, #7c3aed) !important;">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
        <div class="relative flex items-center justify-between flex-wrap gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 text-white uppercase flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Evaluasi Selesai
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $match->result === 'Menang' ? 'bg-emerald-400/30 text-emerald-100' : 'bg-rose-400/30 text-rose-100' }}">
                        {{ $match->result }}
                    </span>
                </div>
                <h3 class="text-xl font-bold">
                    {{ $match->pair->name ?? '-' }} vs {{ $match->opponent_name }}
                </h3>
                <p class="text-xs text-white/70 mt-1 flex items-center gap-2 flex-wrap">
                    <span>📅 {{ $match->match_date->format('d F Y') }}</span>
                    <span>·</span>
                    <span>🏸 {{ $match->pair_category }}</span>
                    <span>·</span>
                    <span>📊 Skor: {{ $match->final_score ?? '-' }}</span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('statistics.show', $match->id) }}" class="btn bg-white/15 text-white hover:bg-white/25 text-xs border border-white/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Statistik
                </a>
                <a href="{{ route('reports.print', $match->id) }}" target="_blank" class="btn bg-white text-primary-600 hover:bg-slate-50 text-xs shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak
                </a>
            </div>
        </div>
    </div>

    {{-- DSS Outputs Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            {{-- =====================================================
                 BLOK 1: CATATAN EVALUASI DARI SISTEM
                 ===================================================== --}}
            <div class="card p-5">
                <h4 class="text-sm font-semibold text-slate-800 pb-3 mb-4 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    Catatan Evaluasi Pertandingan
                </h4>

                @php
                    $evalLines = array_filter(explode("\n", $result->overall_evaluation), fn($l) => trim($l) !== '');
                    $isPositive = count($triggeredDetails) === 0;
                @endphp

                @if($isPositive)
                    <div class="eval-card severity-low">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h5 class="text-sm font-semibold text-emerald-800">Performa Stabil dan Terkendali 👍</h5>
                                <p class="text-xs text-emerald-700 mt-1 leading-relaxed">{{ $result->overall_evaluation }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($evalLines as $line)
                            @php
                                $trimmed = trim($line);
                                $isIntro = !str_starts_with($trimmed, '-');
                            @endphp

                            @if($isIntro)
                                <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $trimmed }}</p>
                                </div>
                            @else
                                <div class="eval-card severity-high">
                                    <div class="flex items-start gap-3">
                                        <div class="w-6 h-6 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs text-slate-700 leading-relaxed">{{ ltrim($trimmed, '- ') }}</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- =====================================================
                 BLOK 2: ATURAN/INDIKATOR YANG TERDETEKSI
                 ===================================================== --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        Masalah yang Terdeteksi
                    </h4>
                    @if($triggeredDetails->count() > 0)
                        <span class="badge bg-rose-100 text-rose-700">{{ $triggeredDetails->count() }} masalah terdeteksi</span>
                    @else
                        <span class="badge badge-active">Tidak ada masalah</span>
                    @endif
                </div>

                @forelse($triggeredDetails as $index => $detail)
                    <div class="border-b border-slate-50 last:border-b-0">
                        {{-- Accordion Header --}}
                        <div class="accordion-trigger flex items-center justify-between px-5 py-3.5 gap-3" onclick="toggleAccordion(this)">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-rose-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    {{-- Nama aturan ditampilkan langsung (sudah human-friendly dari seeder baru) --}}
                                    <h5 class="text-xs font-semibold text-slate-800">{{ $detail->rule_name }}</h5>
                                    {{-- Ganti condition_description teknis dengan kalimat natural --}}
                                    <p class="text-[10px] text-slate-400 truncate">Klik untuk melihat detail penjelasan</p>
                                </div>
                            </div>
                            <svg class="accordion-chevron w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        {{-- Accordion Content --}}
                        <div class="accordion-content">
                            <div class="space-y-3 pt-1">

                                {{-- Kondisi yang Terpenuhi (bahasa pelatih) --}}
                                <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-lg">
                                    <p class="text-[10px] font-bold text-indigo-600 uppercase mb-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Situasi yang Terdeteksi
                                    </p>
                                    <p class="text-xs text-indigo-800 leading-relaxed">{{ $detail->condition_description }}</p>
                                </div>

                                {{-- Catatan Evaluasi / Penjelasan --}}
                                <div class="p-3 bg-rose-50 border border-rose-100 rounded-lg">
                                    <p class="text-[10px] font-bold text-rose-600 uppercase mb-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Penjelasan dari Sistem
                                    </p>
                                    <p class="text-xs text-rose-900 leading-relaxed">{{ $detail->evaluation_result_text }}</p>
                                </div>

                                {{-- Data Aktual / Alasan --}}
                                <div class="p-3 bg-amber-50 border border-amber-100 rounded-lg">
                                    <p class="text-[10px] font-bold text-amber-700 uppercase mb-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        Data dari Pertandingan Ini
                                    </p>
                                    <p class="text-xs text-amber-900 leading-relaxed">{{ $detail->evaluation_reason }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-slate-600">Tidak ada masalah yang terdeteksi</p>
                        <p class="text-xs text-slate-400 mt-1">Semua indikator dalam kondisi normal. Pertahankan performa ini!</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- =====================================================
             KOLOM KANAN: FOKUS LATIHAN + CATATAN PELATIH
             ===================================================== --}}
        <div class="space-y-6">

            {{-- Fokus Latihan --}}
            <div class="card p-5 bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200/60">
                <h4 class="text-sm font-semibold text-amber-900 pb-2 mb-3 uppercase tracking-wider flex items-center gap-2 border-b border-amber-200/50">
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    Rekomendasi Program Latihan
                </h4>

                @php
                    $focusLines = array_filter(explode("\n", $result->improvement_focus), fn($l) => trim($l) !== '');
                    $focusIcons = ['🏋️', '🎯', '🧠', '⚡', '🔄', '💪', '📐', '🏃', '🏸', '🎽'];
                    $iconIndex = 0;
                @endphp

                <div class="space-y-2.5">
                    @foreach($focusLines as $i => $focusLine)
                        @php $trimFocus = trim($focusLine); @endphp
                        @if(str_starts_with($trimFocus, '-'))
                            <div class="focus-item">
                                <div class="focus-item-icon">
                                    {{ $focusIcons[$iconIndex % count($focusIcons)] }}
                                </div>
                                <p class="text-xs text-amber-950 leading-relaxed">{{ ltrim($trimFocus, '- ') }}</p>
                            </div>
                            @php $iconIndex++; @endphp
                        @else
                            <p class="text-xs text-amber-800/80 leading-relaxed px-1">{{ $trimFocus }}</p>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Catatan Pelatih --}}
            <div class="card p-5 glass-card">
                <h4 class="text-sm font-semibold text-slate-800 pb-2 mb-3 uppercase tracking-wider flex items-center gap-2 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    Catatan Pelatih
                </h4>
                <form method="POST" action="{{ route('evaluations.notes', $match->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <textarea id="coach_notes" name="coach_notes" rows="5" 
                              class="form-input text-xs" placeholder="Tambahkan kesimpulan taktis, penilaian mental pemain, atau instruksi latihan khusus dari pelatih...">{{ old('coach_notes', $result->coach_notes) }}</textarea>
                    
                    <button type="submit" class="btn btn-primary w-full justify-center text-xs mt-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Catatan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Bottom Actions --}}
    <div class="flex items-center justify-between flex-wrap gap-3 border-t border-slate-100 pt-4">
        <a href="{{ route('statistics.index') }}" class="btn btn-outline text-xs">
            ← Kembali ke Daftar Statistik
        </a>
        <a href="{{ route('reports.show', $match->id) }}" class="btn btn-outline text-xs">
            Lihat Laporan Lengkap →
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleAccordion(trigger) {
        const content = trigger.nextElementSibling;
        const isOpen = content.classList.contains('open');

        // Close all accordions
        document.querySelectorAll('.accordion-content').forEach(el => el.classList.remove('open'));
        document.querySelectorAll('.accordion-trigger').forEach(el => el.classList.remove('open'));

        // Toggle current
        if (!isOpen) {
            content.classList.add('open');
            trigger.classList.add('open');
        }
    }
</script>
@endpush

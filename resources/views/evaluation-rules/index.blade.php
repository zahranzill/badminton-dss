@extends('layouts.app')

@section('title', 'Aturan Evaluasi')
@section('page-title', 'Aturan Evaluasi')
@section('page-subtitle', 'Daftar aturan penilaian yang digunakan sistem untuk menganalisis pertandingan')

@section('content')
<div class="space-y-5">
    {{-- Header Bar --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-800">Panduan Penilaian Pertandingan</h3>
                <p class="text-xs text-slate-500">{{ $rules->total() }} aturan aktif terdaftar</p>
            </div>
        </div>
        <a href="{{ route('evaluation-rules.create') }}" class="btn btn-primary text-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Aturan
        </a>
    </div>

    {{-- Info Banner --}}
    <div class="p-3.5 bg-blue-50 border border-blue-100 rounded-xl flex items-start gap-3 text-blue-800">
        <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-xs leading-relaxed">Setiap aturan di bawah ini adalah pedoman yang digunakan sistem untuk mengevaluasi performa pasangan secara otomatis. Sistem akan memeriksa kondisi yang ditetapkan, dan jika terpenuhi, akan memberikan catatan evaluasi dan rekomendasi latihan kepada pelatih.</p>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        {{-- Total Aturan --}}
        <div class="card p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-primary-50 rounded-full -translate-y-1/3 translate-x-1/3"></div>
            <div class="relative">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-800">{{ $totalRules }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Total Aturan</p>
            </div>
        </div>

        {{-- Aturan Aktif --}}
        <div class="card p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-50 rounded-full -translate-y-1/3 translate-x-1/3"></div>
            <div class="relative">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-emerald-600">{{ $activeRules }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Aturan Aktif</p>
            </div>
        </div>

        {{-- Aturan Nonaktif --}}
        <div class="card p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-amber-50 rounded-full -translate-y-1/3 translate-x-1/3"></div>
            <div class="relative">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-amber-600">{{ $inactiveRules }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Aturan Nonaktif</p>
            </div>
        </div>

        {{-- Indikator Terukur --}}
        <div class="card p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-violet-50 rounded-full -translate-y-1/3 translate-x-1/3"></div>
            <div class="relative">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-violet-600">{{ $uniqueIndicators }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Indikator Terukur</p>
            </div>
        </div>
    </div>

    {{-- Rules Grid --}}
    @if($rules->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($rules as $rule)
                <div class="rule-card">
                    {{-- Card Header --}}
                    <div class="rule-card-header">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="priority-badge flex-shrink-0">{{ $rule->priority }}</span>
                            <div class="min-w-0">
                                <h4 class="text-sm font-semibold text-slate-800 truncate">{{ $rule->rule_name }}</h4>
                                <p class="text-[10px] text-slate-400 truncate">{{ $rule->indicator }}</p>
                            </div>
                        </div>
                        <span class="badge {{ $rule->is_active ? 'badge-active' : 'badge-inactive' }} flex-shrink-0">
                            {{ $rule->is_active ? 'Aktif' : 'Off' }}
                        </span>
                    </div>

                    {{-- Card Body: Kondisi → Kesimpulan --}}
                    <div class="rule-card-body space-y-3">
                        {{-- Kondisi --}}
                        <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-lg">
                            <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider block mb-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Kapan Aturan Ini Berlaku?
                            </span>
                            <p class="text-xs text-indigo-800 leading-relaxed">{{ $rule->condition_logic }}</p>
                        </div>

                        {{-- Kesimpulan --}}
                        <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-lg">
                            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block mb-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Catatan Evaluasi yang Diberikan:
                            </span>
                            <p class="text-xs text-emerald-800 leading-relaxed line-clamp-3">{{ $rule->evaluation_result }}</p>
                        </div>
                    </div>

                    {{-- Card Footer --}}
                    <div class="rule-card-footer">
                        <a href="{{ route('evaluation-rules.edit', $rule->id) }}" class="btn btn-outline text-xs px-3 py-1.5" title="Ubah">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <button onclick="confirmDelete('{{ route('evaluation-rules.destroy', $rule->id) }}', 'Hapus aturan: {{ $rule->rule_name }}?')" class="btn text-xs px-3 py-1.5 text-rose-600 border border-rose-200 hover:bg-rose-50" title="Hapus">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h4>Belum ada aturan evaluasi</h4>
                <p>Tambahkan aturan penilaian pertama untuk memulai sistem evaluasi otomatis.</p>
            </div>
        </div>
    @endif

    @if($rules->hasPages())
        <div class="flex justify-center">
            {{ $rules->links() }}
        </div>
    @endif
</div>
@endsection

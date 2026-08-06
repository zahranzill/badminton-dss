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

    {{-- =====================================================
         BLOK: GLOBAL KNOWLEDGE GRAPH — PETA PENGETAHUAN SISTEM
         ===================================================== --}}
    <div class="card overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
            <h4 class="text-sm font-semibold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                Knowledge Graph — Peta Basis Pengetahuan DSS
            </h4>
            <div class="flex items-center gap-2">
                <button onclick="globalGraphFitAll()" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 text-[10px] font-medium transition-all" title="Sesuaikan Tampilan">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                    Fit All
                </button>
                <button onclick="toggleGraphFullscreen()" id="btn-fullscreen" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-[10px] font-medium transition-all" title="Layar Penuh">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                    Layar Penuh
                </button>
            </div>
        </div>

        {{-- Info --}}
        <div class="px-5 py-2.5 bg-gradient-to-r from-indigo-50 to-cyan-50 border-b border-slate-100">
            <p class="text-[10px] text-indigo-700 leading-relaxed">
                <strong>Peta ini menampilkan seluruh basis pengetahuan (Knowledge Base)</strong> yang dimiliki sistem DSS PB Garles. 
                Alur: <strong class="text-blue-600">Data Fakta</strong> → <strong class="text-violet-600">Indikator Kinerja</strong> → <strong class="text-rose-600">Aturan IF</strong> → <strong class="text-amber-600">Evaluasi THEN</strong> → <strong class="text-emerald-600">Keputusan DSS</strong>.
                Hover pada node untuk melihat detail. Drag dan scroll untuk navigasi.
            </p>
        </div>

        {{-- Legend --}}
        <div class="px-5 py-2.5 bg-slate-50 border-b border-slate-100 flex flex-wrap items-center gap-x-5 gap-y-2 text-[10px]">
            <span class="font-semibold text-slate-500 uppercase">Legenda:</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-slate-700 inline-block"></span> Inference Engine</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-blue-500 inline-block"></span> Data Input</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-violet-500 inline-block" style="transform:rotate(45deg);width:10px;height:10px;"></span> Indikator Kinerja</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-rose-500 inline-block"></span> Aturan (IF)</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-amber-500 inline-block"></span> Evaluasi (THEN)</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-emerald-500 inline-block"></span> Keputusan Output</span>
        </div>

        {{-- Graph Container --}}
        <div id="global-knowledge-graph-wrapper" class="relative">
            <div id="global-knowledge-graph" style="height: 580px; width: 100%; background: #f8fafc;"></div>
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

@push('scripts')
<script src="https://unpkg.com/vis-network@9.1.6/standalone/umd/vis-network.min.js"></script>
<script>
    let globalGraphNetwork = null;

    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('global-knowledge-graph');
        if (!container) return;

        const graphData = @json($globalGraphData);

        const nodes = new vis.DataSet(graphData.nodes.map(n => ({
            ...n,
            font: {
                size: n.group === 'evaluation' ? 9 : (n.group === 'system' ? 14 : 11),
                color: '#1e293b',
                face: 'Inter, sans-serif',
                multi: 'md',
            },
        })));

        const edges = new vis.DataSet(graphData.edges.map(e => ({
            ...e,
            arrows: { to: { enabled: true, scaleFactor: 0.5 } },
            font: { size: 8, color: '#64748b', face: 'Inter, sans-serif', strokeWidth: 3, strokeColor: '#ffffff' },
            smooth: { type: 'cubicBezier', forceDirection: 'horizontal', roundness: 0.35 },
            width: e.width || 1.5,
        })));

        const options = {
            layout: {
                hierarchical: {
                    enabled: true,
                    direction: 'LR', // Left to Right flow
                    sortMethod: 'directed',
                    nodeSpacing: 120,
                    levelSeparation: 260,
                    treeSpacing: 160,
                    blockShifting: true,
                    edgeMinimization: true,
                    parentCentralization: true
                }
            },
            groups: {
                input: {
                    color: { background: '#3b82f6', border: '#1d4ed8', highlight: { background: '#60a5fa', border: '#1d4ed8' } },
                    font: { color: '#ffffff', size: 11, bold: { color: '#ffffff' } },
                    borderWidth: 2,
                    shadow: { enabled: true, color: 'rgba(59, 130, 246, 0.3)', size: 8 },
                },
                indicator: {
                    color: { background: '#8b5cf6', border: '#6d28d9', highlight: { background: '#a78bfa', border: '#6d28d9' } },
                    font: { color: '#ffffff', size: 10, bold: { color: '#ffffff' } },
                    borderWidth: 2,
                    shadow: { enabled: true, color: 'rgba(139, 92, 246, 0.3)', size: 8 },
                },
                system: {
                    color: { background: '#1e293b', border: '#0f172a', highlight: { background: '#334155', border: '#0f172a' } },
                    font: { color: '#ffffff', size: 12, bold: { color: '#ffffff' } },
                    borderWidth: 3,
                    shadow: { enabled: true, color: 'rgba(30, 41, 59, 0.4)', size: 12 },
                },
                rule: {
                    color: { background: '#f43f5e', border: '#be123c', highlight: { background: '#fb7185', border: '#be123c' } },
                    font: { color: '#ffffff', size: 10 },
                    borderWidth: 2,
                    shadow: { enabled: true, color: 'rgba(244, 63, 94, 0.3)', size: 6 },
                },
                evaluation: {
                    color: { background: '#f59e0b', border: '#b45309', highlight: { background: '#fbbf24', border: '#b45309' } },
                    font: { color: '#1e293b', size: 9 },
                    borderWidth: 1.5,
                    shadow: { enabled: true, color: 'rgba(245, 158, 11, 0.25)', size: 6 },
                },
                output: {
                    color: { background: '#10b981', border: '#047857', highlight: { background: '#34d399', border: '#047857' } },
                    font: { color: '#ffffff', size: 11, bold: { color: '#ffffff' } },
                    borderWidth: 2.5,
                    shadow: { enabled: true, color: 'rgba(16, 185, 129, 0.3)', size: 10 },
                },
            },
            physics: {
                enabled: false // Disabled for stable, deterministic hierarchical rendering
            },
            interaction: {
                hover: true,
                tooltipDelay: 100,
                zoomView: true,
                dragView: true,
                navigationButtons: true,
            },
        };

        globalGraphNetwork = new vis.Network(container, { nodes, edges }, options);

        globalGraphNetwork.once('stabilizationIterationsDone', function() {
            globalGraphNetwork.fit({ animation: { duration: 600, easingFunction: 'easeInOutQuad' } });
        });
    });

    function globalGraphFitAll() {
        if (globalGraphNetwork) {
            globalGraphNetwork.fit({ animation: { duration: 500, easingFunction: 'easeInOutQuad' } });
        }
    }

    function toggleGraphFullscreen() {
        const wrapper = document.getElementById('global-knowledge-graph-wrapper');
        const graphEl = document.getElementById('global-knowledge-graph');
        const btn = document.getElementById('btn-fullscreen');

        if (!wrapper.classList.contains('graph-fullscreen')) {
            wrapper.classList.add('graph-fullscreen');
            wrapper.style.cssText = 'position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:9999;background:#f8fafc;';
            graphEl.style.height = '100vh';
            btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Tutup';
        } else {
            wrapper.classList.remove('graph-fullscreen');
            wrapper.style.cssText = 'position:relative;';
            graphEl.style.height = '580px';
            btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg> Layar Penuh';
        }
        if (globalGraphNetwork) {
            setTimeout(() => {
                globalGraphNetwork.redraw();
                globalGraphNetwork.fit({ animation: { duration: 300 } });
            }, 200);
        }
    }

    // ESC key to exit fullscreen
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const wrapper = document.getElementById('global-knowledge-graph-wrapper');
            if (wrapper && wrapper.classList.contains('graph-fullscreen')) {
                toggleGraphFullscreen();
            }
        }
    });
</script>
@endpush

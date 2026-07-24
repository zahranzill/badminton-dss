@extends('layouts.app')

@section('title', 'Proses Verifikasi Laga')
@section('page-title', 'Proses Verifikasi Laga')
@section('page-subtitle')
    Pertandingan: {{ $match->pair->name ?? '-' }} vs {{ $match->opponent_name }} ({{ $match->match_date->format('d M Y') }})
@endsection

@section('content')
<div class="space-y-6">
    {{-- Summary Cards Check --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card p-4 bg-slate-50 border border-slate-200">
            <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider mb-1">Skor Akhir Riil</span>
            <p class="text-lg font-bold text-slate-800">{{ $match->final_score ?? 'Belum Diisi' }}</p>
            <p class="text-xs text-slate-500 mt-1">Sesuai data input pertandingan</p>
        </div>
        <div class="card p-4 bg-slate-50 border border-slate-200">
            <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider mb-1">Total Rally Diinput</span>
            <p class="text-lg font-bold text-slate-800">{{ $match->rallies->count() }} Rally</p>
            <p class="text-xs text-slate-500 mt-1">Total keseluruhan dari seluruh set</p>
        </div>
        <div class="card p-4 bg-slate-50 border border-slate-200">
            <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider mb-1">Hasil Pertandingan</span>
            <span class="badge {{ $match->result === 'Menang' ? 'badge-win' : 'badge-lose' }} mt-1">
                {{ $match->result }}
            </span>
        </div>
    </div>

    {{-- Checklist kelayakan data --}}
    <div class="card p-5">
        <h4 class="text-sm font-semibold text-slate-800 border-b border-slate-100 pb-3 mb-4 uppercase tracking-wider">
            Checklist Kelayakan Data
        </h4>
        <div class="space-y-3 text-sm">
            {{-- Rule 1: Ada data rally --}}
            <div class="flex items-start gap-3">
                <div class="mt-0.5">
                    @if($match->rallies->count() > 0)
                        <span class="text-emerald-500 font-bold">✔</span>
                    @else
                        <span class="text-rose-500 font-bold">✘</span>
                    @endif
                </div>
                <div>
                    <p class="font-medium text-slate-700">Data Rally Terisi</p>
                    <p class="text-xs text-slate-400">Minimal harus terdapat 1 data rally diinput.</p>
                </div>
            </div>

            {{-- Rule 2: Minimal set --}}
            <div class="flex items-start gap-3">
                <div class="mt-0.5">
                    @if($ralliesBySet->count() >= 2)
                        <span class="text-emerald-500 font-bold">✔</span>
                    @else
                        <span class="text-amber-500 font-bold">⚠</span>
                    @endif
                </div>
                <div>
                    <p class="font-medium text-slate-700">Jumlah Set Terisi ({{ $ralliesBySet->count() }} Set)</p>
                    <p class="text-xs text-slate-400">Umumnya pertandingan bulutangkis dimenangkan dalam 2 atau 3 set.</p>
                </div>
            </div>

            {{-- Rule 3: Validasi kecocokan pemenang poin dengan skor terakhir --}}
            <div class="flex items-start gap-3">
                <div class="mt-0.5">
                    @php
                        $scoreMatches = true;
                        foreach($ralliesBySet as $setNum => $rallies) {
                            $lastRally = $rallies->sortByDesc('rally_number')->first();
                            if ($lastRally) {
                                // Bulutangkis set berakhir di 21 (deuce max 30)
                                $pair = $lastRally->pair_score;
                                $opp = $lastRally->opponent_score;
                                if (!($pair >= 21 || $opp >= 21) && !($pair >= 11 && $opp < 11)) {
                                    // Boleh jadi ada warning kalau skor set belum sampai 21
                                    $scoreMatches = false;
                                }
                            }
                        }
                    @endphp
                    @if($scoreMatches)
                        <span class="text-emerald-500 font-bold">✔</span>
                    @else
                        <span class="text-amber-500 font-bold">⚠</span>
                    @endif
                </div>
                <div>
                    <p class="font-medium text-slate-700">Validasi Skor Set Akhir</p>
                    <p class="text-xs text-slate-400">Memeriksa apakah skor akhir setiap set telah mencapai batas standar poin kemenangan (minimal 21 poin, atau kondisi deuce).</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Rallies grouped by Set --}}
    @foreach($ralliesBySet as $setNum => $rallies)
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <h4 class="text-sm font-semibold text-slate-800">Set {{ $setNum }}</h4>
                <span class="badge badge-final text-xs">{{ $rallies->count() }} rally</span>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table responsive-cards text-xs">
                    <thead>
                        <tr>
                            <th class="w-16">Rally</th>
                            <th class="text-center w-16">Skor</th>
                            <th>Pemenang Poin</th>
                            <th>Hasil Rally</th>
                            <th>Jenis Error</th>
                            <th>Pemain Error</th>
                            <th>Stroke</th>
                            <th>Durasi</th>
                            <th>Kritis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rallies->sortBy('rally_number') as $rally)
                            <tr>
                                <td data-label="Rally">#{{ $rally->rally_number }}</td>
                                <td data-label="Skor" class="text-center font-bold">
                                    <span class="text-primary-600">{{ $rally->pair_score }}</span> - <span class="text-slate-500">{{ $rally->opponent_score }}</span>
                                </td>
                                <td data-label="Pemenang Poin">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $rally->point_winner === 'Pasangan' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $rally->point_winner }}
                                    </span>
                                </td>
                                <td data-label="Hasil Rally">{{ $rally->point_result }}</td>
                                <td data-label="Jenis Error">{{ $rally->error_type ?? '-' }}</td>
                                <td data-label="Pemain Error">{{ $rally->errorPlayer->name ?? '-' }}</td>
                                <td data-label="Stroke">{{ $rally->stroke_count ? $rally->stroke_count . ' pukulan' : '-' }}</td>
                                <td data-label="Durasi">{{ $rally->rally_duration ? $rally->rally_duration . ' dtk' : '-' }}</td>
                                <td data-label="Kritis">
                                    @if($rally->is_critical_point)
                                        <span class="text-rose-500 font-bold">Ya</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    {{-- Bottom Actions / Finalize Modal trigger --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('rallies.index', $match->id) }}" class="btn btn-outline">
                Kembali ke Input Rally
            </a>
        </div>
        <div>
            @if($match->status === 'Draft')
                <button onclick="confirmFinalize()" class="btn btn-success">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Finalisasi Data Pertandingan
                </button>
            @endif
        </div>
    </div>
</div>

{{-- Finalize Confirmation Modal --}}
<div id="finalize-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-xl shadow-2xl p-6 mx-4 max-w-md w-full transform transition-all">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-800">Konfirmasi Finalisasi</h3>
        </div>
        <p class="text-slate-600 mb-6">
            Apakah Anda yakin ingin memfinalisasi data pertandingan ini? Setelah difinalisasi, status akan berubah menjadi <strong>Final</strong>, statistik akan dihitung, dan data rally <strong>tidak dapat diubah atau ditambahkan lagi</strong>.
        </p>
        <div class="flex justify-end gap-3">
            <button onclick="closeFinalizeModal()" class="btn btn-outline">Batal</button>
            <form action="{{ route('verification.finalize', $match->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Finalisasi & Hitung Statistik</button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmFinalize() {
        const modal = document.getElementById('finalize-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeFinalizeModal() {
        const modal = document.getElementById('finalize-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection

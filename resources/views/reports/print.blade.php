<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Evaluasi — {{ $match->pair->name ?? '-' }} vs {{ $match->opponent_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #1a202c;
            padding: 30px;
            font-size: 12px;
            line-height: 1.5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            border-bottom: 3px double #2d3748;
            padding-bottom: 15px;
            margin-bottom: 25px;
            text-align: center;
        }

        .header h1 {
            font-size: 18px;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header p {
            font-size: 10px;
            color: #718096;
            margin: 5px 0 0 0;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            background: #edf2f7;
            padding: 5px 10px;
            border-radius: 4px;
            margin-top: 20px;
            margin-bottom: 12px;
            border-left: 3px solid #2d3748;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .card {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            padding: 10px;
            border-radius: 4px;
        }

        .card span {
            font-size: 9px;
            color: #718096;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }

        .card p, .card strong {
            font-size: 12px;
            margin: 0;
            color: #2d3748;
        }

        .text-bold {
            font-weight: 700;
        }

        .text-rose {
            color: #e53e3e;
        }

        .text-emerald {
            color: #38a169;
        }

        .whitespace-pre-line {
            white-space: pre-line;
        }

        .italic {
            font-style: italic;
        }

        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }

        .signature-box {
            text-align: center;
            width: 220px;
        }

        .signature-line {
            border-top: 1px solid #718096;
            margin-top: 60px;
            padding-top: 5px;
            font-weight: 700;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: 700;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .badge-win {
            background-color: #c6f6d5;
            color: #22543d;
        }

        .badge-lose {
            background-color: #fed7d7;
            color: #742a2a;
        }

        @media print {
            body {
                padding: 0;
                font-size: 11px;
            }

            .container {
                max-width: 100%;
            }

            @page {
                size: A4;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LAPORAN EVALUASI PERTANDINGAN GANDA</h1>
            <p>DECISION SUPPORT SYSTEM (DSS) EVALUASI PERTANDINGAN BULUTANGKIS SEKTOR GANDA</p>
        </div>

        {{-- Identitas Laga --}}
        <div class="section-title">I. Identitas Pertandingan</div>
        <div class="grid-2" style="margin-bottom: 15px;">
            <div>
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <tr>
                        <td style="width: 100px; color: #718096; padding: 4px 0;">Tanggal Laga</td>
                        <td style="padding: 4px 0;">: {{ $match->match_date->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #718096; padding: 4px 0;">Kategori Ganda</td>
                        <td style="padding: 4px 0;">: {{ $match->pair_category }}</td>
                    </tr>
                    <tr>
                        <td style="color: #718096; padding: 4px 0;">Tipe Turnamen</td>
                        <td style="padding: 4px 0;">: {{ $match->match_type }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <tr>
                        <td style="width: 100px; color: #718096; padding: 4px 0;">Pasangan Ganda</td>
                        <td style="padding: 4px 0;">: <strong>{{ $match->pair->name ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #718096; padding: 4px 0;">Susunan Pemain</td>
                        <td style="padding: 4px 0;">: {{ $match->pair->player1->name ?? '-' }} / {{ $match->pair->player2->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #718096; padding: 4px 0;">Pasangan Lawan</td>
                        <td style="padding: 4px 0;">: <strong>{{ $match->opponent_name }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Hasil & Skor --}}
        <div class="section-title">II. Hasil Akhir Pertandingan</div>
        <div class="grid-2" style="margin-bottom: 15px;">
            <div class="card">
                <span>Hasil Pertandingan</span>
                <span class="badge {{ $match->result === 'Menang' ? 'badge-win' : 'badge-lose' }}">
                    {{ $match->result }}
                </span>
            </div>
            <div class="card">
                <span>Skor Akhir Laga</span>
                <strong>{{ $match->final_score ?? '-' }}</strong>
            </div>
        </div>

        {{-- Statistik performa --}}
        <div class="section-title">III. Ringkasan Statistik Performa</div>
        @php
            $stats = $match->performanceStatistic;
        @endphp
        @if($stats)
            <div class="grid-4" style="margin-bottom: 10px;">
                <div class="card">
                    <span>Total Rally</span>
                    <p class="text-bold">{{ $stats->total_rallies }} rally</p>
                </div>
                <div class="card">
                    <span>Poin Sendiri</span>
                    <p class="text-bold text-emerald">{{ $stats->pair_points }} ({{ $stats->pair_point_percentage }}%)</p>
                </div>
                <div class="card">
                    <span>Poin Lawan</span>
                    <p class="text-bold text-rose">{{ $stats->opponent_points }} ({{ $stats->opponent_point_percentage }}%)</p>
                </div>
                <div class="card">
                    <span>Unforced Error</span>
                    <p class="text-bold text-rose">{{ $stats->pair_errors }} error</p>
                </div>
            </div>
            <div class="grid-4" style="margin-bottom: 15px;">
                <div class="card">
                    <span>Jenis Error Dominan</span>
                    <p class="text-bold">{{ $stats->dominant_error_type ?? 'Tidak Ada' }}</p>
                </div>
                <div class="card">
                    <span>Paling Sering Error</span>
                    <p class="text-bold text-rose">{{ $stats->mostErrorPlayer->name ?? '-' }} ({{ $stats->most_error_player_count }}x)</p>
                </div>
                <div class="card">
                    <span>Rerata Pukulan / Rally</span>
                    <p class="text-bold">{{ $stats->avg_stroke_count }} pukulan</p>
                </div>
                <div class="card">
                    <span>Rerata Durasi Rally</span>
                    <p class="text-bold">{{ $stats->avg_rally_duration }} dtk</p>
                </div>
            </div>
        @endif

        {{-- Hasil Evaluasi DSS --}}
        @php
            $result = $match->evaluationResult;
        @endphp
        @if($result)
            <div class="section-title">IV. Kesimpulan Evaluasi DSS</div>
            <div class="card whitespace-pre-line" style="margin-bottom: 15px; font-size: 11px;">
                {{ $result->overall_evaluation }}
            </div>

            <div class="section-title">V. Rekomendasi Program Latihan</div>
            <div class="card whitespace-pre-line" style="margin-bottom: 15px; font-size: 11px;">
                {{ $result->improvement_focus }}
            </div>

            @if($result->coach_notes)
                <div class="section-title">VI. Catatan Khusus Pelatih</div>
                <div class="card italic" style="margin-bottom: 15px; font-size: 11px;">
                    "{{ $result->coach_notes }}"
                </div>
            @endif
        @endif

        {{-- Tanda Tangan --}}
        <div class="signature">
            <div class="signature-box">
                <p>Dilaporkan oleh,</p>
                <div class="signature-line">
                    {{ Auth::user()->name }}
                    <p style="margin: 2px 0 0 0; font-size: 9px; font-weight: normal; color: #718096;">Pelatih Ganda Utama</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            const params = new URLSearchParams(window.location.search);
            const action = params.get('action');

            if (action === 'pdf') {
                // Show PDF instruction banner
                const banner = document.createElement('div');
                banner.id = 'pdf-banner';
                banner.style.cssText = 'position:fixed;top:0;left:0;right:0;background:linear-gradient(135deg,#1e293b,#334155);color:#fff;padding:14px 24px;display:flex;align-items:center;justify-content:space-between;z-index:9999;font-family:Inter,sans-serif;box-shadow:0 4px 12px rgba(0,0,0,0.15);';
                banner.innerHTML = `
                    <div style="display:flex;align-items:center;gap:12px;">
                        <svg style="width:20px;height:20px;color:#f87171;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <div>
                            <strong style="font-size:13px;">Unduh sebagai PDF</strong>
                            <p style="font-size:11px;color:#94a3b8;margin:2px 0 0 0;">Klik tombol di samping → pada dialog cetak, pilih <strong style="color:#f87171;">"Save as PDF"</strong> sebagai Destination.</p>
                        </div>
                    </div>
                    <button onclick="window.print(); document.getElementById('pdf-banner').style.display='none';" style="background:#f43f5e;color:#fff;border:none;padding:8px 18px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;font-family:Inter,sans-serif;">Simpan PDF</button>
                `;
                document.body.prepend(banner);
                document.body.style.paddingTop = '60px';
            } else {
                // Direct print
                window.print();
            }
        }
    </script>
</body>
</html>

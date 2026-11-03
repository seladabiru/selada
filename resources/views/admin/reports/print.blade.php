<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Rekapitulasi Siaran Lagu - {{ config('app.name', 'Selada') }}</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 11pt; color: #000; background: #fff; }
            @page { margin: 1.5cm; }
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            background: #f9fafb;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .kop {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .kop h2 { margin: 0; font-size: 18pt; text-transform: uppercase; letter-spacing: 1px; }
        .kop h3 { margin: 5px 0 0; font-size: 14pt; font-weight: normal; }
        .kop p { margin: 4px 0 0; font-size: 9pt; color: #555; }
        .title {
            text-align: center;
            margin: 20px 0;
        }
        .title h4 {
            margin: 0;
            font-size: 13pt;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .title p {
            margin: 5px 0 0;
            font-size: 10pt;
            color: #555;
        }
        .summary-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px 15px;
            font-size: 10pt;
            background: #fbfbfb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 10pt;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            font-size: 10pt;
        }
        .sig-col {
            text-align: center;
            width: 200px;
        }
        .sig-space {
            height: 70px;
        }
        .btn-print {
            background: #4f46e5;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="no-print" style="max-width: 900px; margin: 0 auto 15px; text-align: right;">
        <button class="btn-print" onclick="window.print()">🖨 Cetak Dokumen / Simpan PDF</button>
    </div>

    <div class="container">
        {{-- Kop Surat Sekolah --}}
        <div class="kop">
            <h2>ORGANISASI SISWA INTRA SEKOLAH (OSIS)</h2>
            <h3>DIVISI SIARAN & INFORMASI RADIO SEKOLAH</h3>
            <p>Sistem Pemutar & Request Lagu Siswa (Selada)</p>
        </div>

        {{-- Judul Laporan --}}
        <div class="title">
            <h4>Laporan Rekapitulasi Pemutaran Lagu</h4>
            <p>Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</p>
        </div>

        {{-- Ringkasan --}}
        <div class="summary-box">
            <div><strong>Total Request:</strong> {{ $totalRequest }} lagu</div>
            <div><strong>Diputar:</strong> {{ $totalSelesai }} lagu</div>
            <div><strong>Ditolak:</strong> {{ $totalDitolak }} lagu</div>
            <div><strong>Total Mengudara:</strong> {{ $totalDurasi }} menit</div>
        </div>

        {{-- Tabel Data --}}
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 30px;">No</th>
                    <th style="width: 80px;">Tanggal</th>
                    <th>Judul Lagu & Artis</th>
                    <th style="width: 140px;">Pemohon</th>
                    <th class="text-center" style="width: 60px;">Durasi</th>
                    <th class="text-center" style="width: 80px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($songs as $idx => $song)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>{{ $song->created_at->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $song->judul }}</strong><br>
                            <span style="font-size: 8.5pt; color: #555;">{{ $song->channel ?: '—' }}</span>
                        </td>
                        <td>
                            {{ $song->user?->name ?: '—' }}<br>
                            <span style="font-size: 8.5pt; color: #555;">{{ $song->user?->kelas ?: 'Siswa' }} ({{ $song->user?->nis }})</span>
                        </td>
                        <td class="text-center">{{ $song->durasi > 0 ? $song->durasiFormatted() : '—' }}</td>
                        <td class="text-center">
                            {{ $song->statusLabel() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 20px;">
                            Tidak ada data siaran pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Tanda Tangan Pengesahan --}}
        <div class="signatures">
            <div class="sig-col">
                <p>Mengetahui,<br>Pembina OSIS</p>
                <div class="sig-space"></div>
                <p><strong>(.......................................)</strong><br>NIP. -</p>
            </div>

            <div class="sig-col">
                <p>Dicetak pada: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>Operator Radio OSIS</p>
                <div class="sig-space"></div>
                <p><strong>{{ auth()->user()?->name ?? 'Operator' }}</strong><br>NIS. {{ auth()->user()?->nis ?? '-' }}</p>
            </div>
        </div>
    </div>

</body>
</html>


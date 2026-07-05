<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Gudang</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .kop {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px double #333;
        }
        .kop .nama-instansi {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 3px 0;
        }
        .kop .alamat {
            font-size: 9px;
            color: #555;
            margin: 0;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 15px 0 5px 0;
            text-transform: uppercase;
        }
        .sub-title {
            text-align: center;
            font-size: 9px;
            color: #666;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #ccc;
            font-size: 9px;
            text-transform: uppercase;
        }
        td {
            padding: 5px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        tr:nth-child(even) td {
            background-color: #fafafa;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .summary {
            margin-bottom: 15px;
            font-size: 10px;
        }
        .summary table {
            width: auto;
            margin: 0;
        }
        .summary td {
            border: none;
            padding: 2px 10px 2px 0;
            background: none;
        }
        .keterangan {
            margin-top: 20px;
            font-size: 9px;
            color: #555;
        }
        .footer {
            text-align: center;
            font-size: 8px;
            color: #999;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        .page-number {
            text-align: center;
            font-size: 8px;
            color: #999;
            margin-top: 10px;
        }
        .info-print {
            font-size: 8px;
            color: #888;
            text-align: right;
            margin-bottom: 10px;
        }
        @page {
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="kop">
        <p class="nama-instansi">{{ \App\Models\Setting::getValue('nama_instansi', 'SIMANTAP') }}</p>
        <p class="alamat">{{ \App\Models\Setting::getValue('alamat', '') }}</p>
        <p class="alamat">{{ \App\Models\Setting::getValue('kota', '') }} Telp. {{ \App\Models\Setting::getValue('nomor_telp', '') }}</p>
    </div>

    <div class="title">LAPORAN STOK GUDANG</div>
    <div class="sub-title">Periode: {{ isset($filters['dari']) && $filters['dari'] ? \Carbon\Carbon::parse($filters['dari'])->translatedFormat('d F Y') : 'Awal' }} s.d. {{ isset($filters['sampai']) && $filters['sampai'] ? \Carbon\Carbon::parse($filters['sampai'])->translatedFormat('d F Y') : 'Sekarang' }}</div>

    <div class="info-print">Dicetak pada: {{ $dicetakPada }}</div>

    @php
        $totalStok = $stoks->sum('jumlah_stok');
        $totalNilai = $stoks->sum(fn($s) => $s->harga_satuan * $s->jumlah_stok);
        $lowStockCount = $stoks->filter(fn($s) => $s->isLowStock())->count();
    @endphp

    <div class="summary">
        <table>
            <tr>
                <td><strong>Total Item Stok:</strong></td>
                <td>{{ $stoks->count() }}</td>
                <td><strong>Total Nilai Stok:</strong></td>
                <td>Rp {{ number_format($totalNilai, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Jumlah:</strong></td>
                <td>{{ number_format($totalStok, 0, ',', '.') }}</td>
                <td><strong>Stok Menipis:</strong></td>
                <td>{{ $lowStockCount }} item</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Stok</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th class="text-right">Jumlah Stok</th>
                <th class="text-right">Ambang Batas</th>
                <th>Satuan</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-right">Total Nilai</th>
                <th>Vendor</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stoks as $stock)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $stock->nama }}</td>
                    <td>{{ $stock->kategori?->nama ?? '-' }}</td>
                    <td>{{ $stock->lokasi?->nama ?? '-' }}</td>
                    <td class="text-right">{{ number_format($stock->jumlah_stok, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($stock->ambang_batas_minimum, 0, ',', '.') }}</td>
                    <td>{{ $stock->satuan ?? '-' }}</td>
                    <td class="text-right">{{ $stock->harga_satuan ? 'Rp ' . number_format($stock->harga_satuan, 0, ',', '.') : '-' }}</td>
                    <td class="text-right">{{ $stock->harga_satuan ? 'Rp ' . number_format($stock->harga_satuan * $stock->jumlah_stok, 0, ',', '.') : '-' }}</td>
                    <td>{{ $stock->vendor ?? '-' }}</td>
                    <td class="text-center">
                        @if ($stock->isLowStock())
                            <span style="color: #dc2626; font-weight: bold;">MENIPIS</span>
                        @else
                            <span style="color: #16a34a;">Aman</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data stok</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="keterangan">
        <p><strong>Keterangan:</strong><br>
        - Stok dengan status <strong>MENIPIS</strong> berarti jumlah stok sudah mencapai atau berada di bawah ambang batas minimum.<br>
        - Disarankan untuk segera melakukan pengadaan stok yang menipis.</p>
    </div>

    <div class="footer">
        Dicetak pada {{ $dicetakPada }} oleh {{ auth()->user()?->name ?? 'Sistem' }}
    </div>
    <div class="page-number">
        Halaman <span class="pageNumber"></span>
    </div>
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $pdf->page_text(500, 15, $text, null, 8, array(0.6, 0.6, 0.6));
        }
    </script>
</body>
</html>

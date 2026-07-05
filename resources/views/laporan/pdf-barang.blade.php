<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $judul }}</title>
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
        .lokasi-header td {
            background-color: #e8e8e8;
            font-weight: bold;
            font-size: 10px;
            padding: 6px 8px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
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
        .page-break {
            page-break-after: always;
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

    <div class="title">{{ $judul }}</div>
    <div class="sub-title">Periode: {{ isset($filters['dari']) && $filters['dari'] ? \Carbon\Carbon::parse($filters['dari'])->translatedFormat('d F Y') : 'Awal' }} s.d. {{ isset($filters['sampai']) && $filters['sampai'] ? \Carbon\Carbon::parse($filters['sampai'])->translatedFormat('d F Y') : 'Sekarang' }}</div>

    <div class="info-print">Dicetak pada: {{ $dicetakPada }}</div>

    @if ($jenisLaporan === 'barang-lokasi')
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Aset</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Tanggal Pengadaan</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($lokasis as $lokasi)
                    @php $barangs = $lokasi->items; @endphp
                    @if ($barangs->isEmpty()) @continue @endif
                    <tr class="lokasi-header">
                        <td colspan="9">{{ $lokasi->nama }} ({{ $barangs->count() }} barang)</td>
                    </tr>
                    @foreach ($barangs as $barang)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $barang->kode_aset }}</td>
                            <td>{{ $barang->nama }}</td>
                            <td>{{ $barang->kategori?->nama ?? '-' }}</td>
                            <td>{{ $barang->kondisi }}</td>
                            <td>{{ $barang->status_penggunaan }}</td>
                            <td class="text-center">{{ $barang->jumlah }}</td>
                            <td class="text-right">{{ $barang->harga ? 'Rp ' . number_format($barang->harga, 0, ',', '.') : '-' }}</td>
                            <td>{{ $barang->tanggal_pengadaan?->translatedFormat('d/m/Y') ?? '-' }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

    @elseif ($jenisLaporan === 'barang-rusak')
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Aset</th>
                    <th>Nama Barang</th>
                    <th>Lokasi</th>
                    <th>Kategori</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Riwayat Perbaikan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $barang)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $barang->kode_aset }}</td>
                        <td>{{ $barang->nama }}</td>
                        <td>{{ $barang->lokasi?->nama ?? '-' }}</td>
                        <td>{{ $barang->kategori?->nama ?? '-' }}</td>
                        <td>{{ $barang->kondisi }}</td>
                        <td>{{ $barang->status_penggunaan }}</td>
                        <td class="text-center">{{ $barang->repairHistories->count() }}x</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada barang rusak</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @elseif ($jenisLaporan === 'stok-gudang')
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
                        <td>{{ $stock->satuan }}</td>
                        <td class="text-right">{{ $stock->harga_satuan ? 'Rp ' . number_format($stock->harga_satuan, 0, ',', '.') : '-' }}</td>
                        <td class="text-center">{{ $stock->isLowStock() ? 'Menipis' : 'Aman' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data stok</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @elseif ($jenisLaporan === 'riwayat-perbaikan')
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Lokasi</th>
                    <th>Deskripsi Kerusakan</th>
                    <th>Tingkat</th>
                    <th>Ditangani Oleh</th>
                    <th>Biaya</th>
                    <th>Status Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayats as $rh)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $rh->tanggal_laporan?->translatedFormat('d/m/Y') ?? '-' }}</td>
                        <td>{{ $rh->item?->nama ?? '-' }}<br><small>{{ $rh->item?->kode_aset ?? '' }}</small></td>
                        <td>{{ $rh->item?->lokasi?->nama ?? '-' }}</td>
                        <td>{{ Str::limit($rh->deskripsi_kerusakan, 40) }}</td>
                        <td class="text-center">{{ $rh->tingkat_kerusakan }}</td>
                        <td>{{ $rh->penangan?->name ?? ($rh->vendor_eksternal ?? '-') }}</td>
                        <td class="text-right">{{ $rh->biaya ? 'Rp ' . number_format($rh->biaya, 0, ',', '.') : '-' }}</td>
                        <td class="text-center">{{ $rh->status_akhir ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada riwayat perbaikan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

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

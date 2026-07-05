<?php

namespace App\Exports;

use App\Models\Item;
use App\Models\Stock;
use App\Models\Location;
use App\Models\RepairHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;

class LaporanExportPdf
{
    protected string $jenisLaporan;
    protected array $filters;

    public function __construct(string $jenisLaporan, array $filters)
    {
        $this->jenisLaporan = $jenisLaporan;
        $this->filters = $filters;
    }

    public function download(): DomPDF
    {
        $judul = match ($this->jenisLaporan) {
            'barang-lokasi' => 'Rekap Barang per Lokasi',
            'barang-rusak'  => 'Rekap Barang Rusak',
            'stok-gudang'   => 'Rekap Stok Gudang',
            'riwayat-perbaikan' => 'Riwayat Perbaikan',
            default => 'Laporan',
        };

        $data = $this->getData();

        $pdf = Pdf::loadView('laporan.pdf-barang', array_merge($data, [
            'judul' => $judul,
            'jenisLaporan' => $this->jenisLaporan,
            'dicetakPada' => now()->translatedFormat('d F Y H:i:s'),
        ]));

        $pdf->setPaper('A4', 'landscape');

        return $pdf;
    }

    protected function getData(): array
    {
        return match ($this->jenisLaporan) {
            'barang-lokasi' => $this->getBarangPerLokasi(),
            'barang-rusak'  => $this->getBarangRusak(),
            'stok-gudang'   => $this->getStokGudang(),
            'riwayat-perbaikan' => $this->getRiwayatPerbaikan(),
            default => $this->getBarangPerLokasi(),
        };
    }

    protected function getBarangPerLokasi(): array
    {
        $lokasis = Location::where('is_active', true)
            ->with(['items' => function ($q) {
                $q->when(!empty($this->filters['kategori']), fn($q) => $q->where('kategori_id', $this->filters['kategori']))
                  ->when(!empty($this->filters['kondisi']), fn($q) => $q->where('kondisi', $this->filters['kondisi']))
                  ->when(!empty($this->filters['status']), fn($q) => $q->where('status_penggunaan', $this->filters['status']))
                  ->when(!empty($this->filters['dari']), fn($q) => $q->whereDate('tanggal_pengadaan', '>=', $this->filters['dari']))
                  ->when(!empty($this->filters['sampai']), fn($q) => $q->whereDate('tanggal_pengadaan', '<=', $this->filters['sampai']));
            }])
            ->when(!empty($this->filters['lokasi']), fn($q) => $q->where('id', $this->filters['lokasi']))
            ->orderBy('nama')
            ->get();

        return compact('lokasis');
    }

    protected function getBarangRusak(): array
    {
        $items = Item::with(['kategori', 'lokasi', 'repairHistories'])
            ->whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan'])
            ->when(!empty($this->filters['lokasi']), fn($q) => $q->where('lokasi_id', $this->filters['lokasi']))
            ->when(!empty($this->filters['kategori']), fn($q) => $q->where('kategori_id', $this->filters['kategori']))
            ->when(!empty($this->filters['kondisi']), fn($q) => $q->where('kondisi', $this->filters['kondisi']))
            ->when(!empty($this->filters['dari']), fn($q) => $q->whereDate('tanggal_pengadaan', '>=', $this->filters['dari']))
            ->when(!empty($this->filters['sampai']), fn($q) => $q->whereDate('tanggal_pengadaan', '<=', $this->filters['sampai']))
            ->orderBy('kondisi')
            ->orderBy('nama')
            ->get();

        return compact('items');
    }

    protected function getStokGudang(): array
    {
        $stoks = Stock::with(['kategori', 'lokasi'])
            ->withCount('movements')
            ->when(!empty($this->filters['lokasi']), fn($q) => $q->where('lokasi_id', $this->filters['lokasi']))
            ->when(!empty($this->filters['kategori']), fn($q) => $q->where('kategori_id', $this->filters['kategori']))
            ->orderBy('nama')
            ->get();

        return compact('stoks');
    }

    protected function getRiwayatPerbaikan(): array
    {
        $riwayats = RepairHistory::with(['item', 'pelapor', 'penangan', 'item.lokasi', 'item.kategori'])
            ->when(!empty($this->filters['lokasi']), function ($q) {
                $q->whereHas('item', fn($q) => $q->where('lokasi_id', $this->filters['lokasi']));
            })
            ->when(!empty($this->filters['kategori']), function ($q) {
                $q->whereHas('item', fn($q) => $q->where('kategori_id', $this->filters['kategori']));
            })
            ->when(!empty($this->filters['dari']), fn($q) => $q->whereDate('tanggal_laporan', '>=', $this->filters['dari']))
            ->when(!empty($this->filters['sampai']), fn($q) => $q->whereDate('tanggal_laporan', '<=', $this->filters['sampai']))
            ->orderByDesc('tanggal_laporan')
            ->get();

        return compact('riwayats');
    }
}

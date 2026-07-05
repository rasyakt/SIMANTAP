<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BarangExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, ShouldAutoSize
{
    protected string $jenisLaporan;
    protected array $filters;

    public function __construct(string $jenisLaporan, array $filters)
    {
        $this->jenisLaporan = $jenisLaporan;
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Item::with(['kategori', 'lokasi', 'creator']);

        if ($this->jenisLaporan === 'barang-rusak') {
            $query->whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan'])
                  ->withCount('repairHistories');
        }

        if (!empty($this->filters['lokasi'])) {
            $query->where('lokasi_id', $this->filters['lokasi']);
        }

        if (!empty($this->filters['kategori'])) {
            $query->where('kategori_id', $this->filters['kategori']);
        }

        if (!empty($this->filters['kondisi'])) {
            $query->where('kondisi', $this->filters['kondisi']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status_penggunaan', $this->filters['status']);
        }

        if (!empty($this->filters['dari'])) {
            $query->whereDate('tanggal_pengadaan', '>=', $this->filters['dari']);
        }

        if (!empty($this->filters['sampai'])) {
            $query->whereDate('tanggal_pengadaan', '<=', $this->filters['sampai']);
        }

        return $query->orderBy('kode_aset')->get();
    }

    public function headings(): array
    {
        if ($this->jenisLaporan === 'barang-rusak') {
            return [
                'Kode Aset', 'Nama Barang', 'Kategori', 'Lokasi',
                'Nomor Seri', 'Kondisi', 'Status', 'Jumlah',
                'Satuan', 'Harga', 'Jumlah Perbaikan', 'Tanggal Pengadaan'
            ];
        }

        return [
            'Kode Aset', 'Nama Barang', 'Kategori', 'Lokasi',
            'Nomor Seri', 'Kondisi', 'Status Penggunaan', 'Jumlah',
            'Satuan', 'Harga', 'Vendor', 'Tanggal Pengadaan'
        ];
    }

    public function map($item): array
    {
        $base = [
            $item->kode_aset,
            $item->nama,
            $item->kategori?->nama ?? '-',
            $item->lokasi?->nama ?? '-',
            $item->nomor_seri ?? '-',
            $item->kondisi,
            $item->status_penggunaan,
            $item->jumlah,
            $item->satuan ?? '-',
            $item->harga ? 'Rp ' . number_format($item->harga, 0, ',', '.') : '-',
        ];

        if ($this->jenisLaporan === 'barang-rusak') {
            $base[] = $item->repair_histories_count ?? 0;
            $base[] = $item->tanggal_pengadaan?->format('d/m/Y') ?? '-';
        } else {
            $base[] = $item->vendor ?? '-';
            $base[] = $item->tanggal_pengadaan?->format('d/m/Y') ?? '-';
        }

        return $base;
    }

    public function title(): string
    {
        return $this->jenisLaporan === 'barang-rusak'
            ? 'Barang Rusak'
            : 'Barang per Lokasi';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}

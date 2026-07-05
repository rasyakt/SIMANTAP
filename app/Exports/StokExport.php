<?php

namespace App\Exports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StokExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, ShouldAutoSize
{
    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Stock::with(['kategori', 'lokasi'])
            ->withCount('movements');

        if (!empty($this->filters['lokasi'])) {
            $query->where('lokasi_id', $this->filters['lokasi']);
        }

        if (!empty($this->filters['kategori'])) {
            $query->where('kategori_id', $this->filters['kategori']);
        }

        if (!empty($this->filters['dari'])) {
            $query->whereDate('created_at', '>=', $this->filters['dari']);
        }

        if (!empty($this->filters['sampai'])) {
            $query->whereDate('created_at', '<=', $this->filters['sampai']);
        }

        return $query->orderBy('nama')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Stok', 'Kategori', 'Lokasi', 'Jumlah Stok',
            'Ambang Batas', 'Satuan', 'Harga Satuan', 'Vendor',
            'Total Mutasi', 'Status Stok', 'Catatan'
        ];
    }

    public function map($stock): array
    {
        return [
            $stock->nama,
            $stock->kategori?->nama ?? '-',
            $stock->lokasi?->nama ?? '-',
            $stock->jumlah_stok,
            $stock->ambang_batas_minimum,
            $stock->satuan ?? '-',
            $stock->harga_satuan ? 'Rp ' . number_format($stock->harga_satuan, 0, ',', '.') : '-',
            $stock->vendor ?? '-',
            $stock->movements_count,
            $stock->isLowStock() ? 'Menipis' : 'Aman',
            $stock->catatan ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Stok Gudang';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}

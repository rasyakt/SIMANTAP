<?php

namespace App\Livewire\Laporan;

use App\Models\Item;
use App\Models\Stock;
use App\Models\Category;
use App\Models\Location;
use App\Models\RepairHistory;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\BarangExport;
use App\Exports\StokExport;
use App\Exports\LaporanExportPdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Laporan')]
class LaporanIndex extends Component
{
    use WithPagination;

    #[Url(as: 'jenis', history: true)]
    public string $jenisLaporan = 'barang-lokasi';

    #[Url(as: 'lokasi', history: true)]
    public string $filterLokasi = '';

    #[Url(as: 'kategori', history: true)]
    public string $filterKategori = '';

    #[Url(as: 'kondisi', history: true)]
    public string $filterKondisi = '';

    #[Url(as: 'status', history: true)]
    public string $filterStatus = '';

    #[Url(as: 'dari', history: true)]
    public string $filterTanggalDari = '';

    #[Url(as: 'sampai', history: true)]
    public string $filterTanggalSampai = '';

    public array $kondisiOptions = [
        'Baik', 'Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan', 'Sudah Diperbaiki', 'Afkir-Dihapuskan'
    ];

    public array $statusOptions = [
        'Digunakan', 'Idle', 'Dipinjam', 'Dalam Perbaikan', 'Menunggu Pembuangan'
    ];

    public array $jenisLaporanOptions = [
        'barang-lokasi' => 'Rekap Barang per Lokasi',
        'barang-rusak'  => 'Rekap Barang Rusak',
        'stok-gudang'   => 'Rekap Stok Gudang',
        'riwayat-perbaikan' => 'Riwayat Perbaikan',
    ];

    public function resetFilters(): void
    {
        $this->reset(['filterLokasi', 'filterKategori', 'filterKondisi', 'filterStatus', 'filterTanggalDari', 'filterTanggalSampai']);
        $this->resetPage();
    }

    public function exportExcel()
    {
        $this->authorize('laporan.export');

        $filters = $this->getFilters();
        $jenisLabel = $this->jenisLaporanOptions[$this->jenisLaporan] ?? $this->jenisLaporan;

        activity('laporan')
            ->causedBy(\Illuminate\Support\Facades\Auth::user())
            ->event('exported')
            ->withProperties([
                'jenis_laporan' => $this->jenisLaporan,
                'format' => 'Excel',
                'filters' => $filters,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log("Mengekspor laporan {$jenisLabel} ke Excel.");

        if ($this->jenisLaporan === 'stok-gudang') {
            return Excel::download(new StokExport($filters), 'laporan-stok-gudang.xlsx');
        }

        return Excel::download(new BarangExport($this->jenisLaporan, $filters), 'laporan-barang.xlsx');
    }

    public function exportPdf()
    {
        $this->authorize('laporan.export');

        $filters = $this->getFilters();
        $judul = $this->jenisLaporanOptions[$this->jenisLaporan] ?? 'Laporan';
        $jenisLabel = $this->jenisLaporanOptions[$this->jenisLaporan] ?? $this->jenisLaporan;
        $data = $this->getReportData();

        $view = $this->jenisLaporan === 'stok-gudang' ? 'laporan.pdf-stok' : 'laporan.pdf-barang';

        $pdf = Pdf::loadView($view, array_merge($data, [
            'judul' => $judul,
            'jenisLaporan' => $this->jenisLaporan,
            'dicetakPada' => now()->translatedFormat('d F Y H:i:s'),
            'filters' => $filters,
        ]));

        activity('laporan')
            ->causedBy(\Illuminate\Support\Facades\Auth::user())
            ->event('exported')
            ->withProperties([
                'jenis_laporan' => $this->jenisLaporan,
                'format' => 'PDF',
                'filters' => $filters,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log("Mengekspor laporan {$jenisLabel} ke PDF.");

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-' . $this->jenisLaporan . '.pdf');
    }

    private function getFilters(): array
    {
        return [
            'lokasi' => $this->filterLokasi,
            'kategori' => $this->filterKategori,
            'kondisi' => $this->filterKondisi,
            'status' => $this->filterStatus,
            'dari' => $this->filterTanggalDari,
            'sampai' => $this->filterTanggalSampai,
        ];
    }

    private function getReportData(): array
    {
        return match ($this->jenisLaporan) {
            'barang-lokasi' => $this->getBarangPerLokasi(),
            'barang-rusak'  => $this->getBarangRusak(),
            'stok-gudang'   => $this->getStokGudang(),
            'riwayat-perbaikan' => $this->getRiwayatPerbaikan(),
            default => $this->getBarangPerLokasi(),
        };
    }

    private function getBarangPerLokasi(): array
    {
        $lokasis = Location::where('is_active', true)
            ->with(['items' => function ($q) {
                $q->with(['kategori', 'lokasi'])
                  ->when($this->filterKategori, fn($q) => $q->where('kategori_id', $this->filterKategori))
                  ->when($this->filterKondisi, fn($q) => $q->where('kondisi', $this->filterKondisi))
                  ->when($this->filterStatus, fn($q) => $q->where('status_penggunaan', $this->filterStatus))
                  ->when($this->filterTanggalDari, fn($q) => $q->whereDate('tanggal_pengadaan', '>=', $this->filterTanggalDari))
                  ->when($this->filterTanggalSampai, fn($q) => $q->whereDate('tanggal_pengadaan', '<=', $this->filterTanggalSampai));
            }])
            ->when($this->filterLokasi, fn($q) => $q->where('id', $this->filterLokasi))
            ->orderBy('nama')
            ->get();

        $rows = [];
        foreach ($lokasis as $lokasi) {
            $barangs = $lokasi->items;
            if ($barangs->isEmpty()) {
                continue;
            }
            $rows[] = [
                'type' => 'header',
                'key' => 'lokasi-header-' . $lokasi->id,
                'lokasi' => $lokasi,
                'count' => $barangs->count()
            ];
            foreach ($barangs as $barang) {
                $rows[] = [
                    'type' => 'barang',
                    'key' => 'barang-row-' . $barang->id,
                    'barang' => $barang
                ];
            }
        }

        return [
            'lokasis' => $lokasis,
            'reportRows' => $rows
        ];
    }

    private function getBarangRusak(): array
    {
        $items = Item::with(['kategori', 'lokasi', 'repairHistories' => function ($q) {
                $q->latest();
            }])
            ->whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan'])
            ->when($this->filterLokasi, fn($q) => $q->where('lokasi_id', $this->filterLokasi))
            ->when($this->filterKategori, fn($q) => $q->where('kategori_id', $this->filterKategori))
            ->when($this->filterKondisi, fn($q) => $q->where('kondisi', $this->filterKondisi))
            ->when($this->filterTanggalDari, fn($q) => $q->whereDate('tanggal_pengadaan', '>=', $this->filterTanggalDari))
            ->when($this->filterTanggalSampai, fn($q) => $q->whereDate('tanggal_pengadaan', '<=', $this->filterTanggalSampai))
            ->orderBy('kondisi')
            ->orderBy('nama')
            ->get();

        return compact('items');
    }

    private function getStokGudang(): array
    {
        $stoks = Stock::with(['kategori', 'lokasi'])
            ->withCount('movements')
            ->when($this->filterLokasi, fn($q) => $q->where('lokasi_id', $this->filterLokasi))
            ->when($this->filterKategori, fn($q) => $q->where('kategori_id', $this->filterKategori))
            ->orderBy('nama')
            ->get();

        return compact('stoks');
    }

    private function getRiwayatPerbaikan(): array
    {
        $riwayats = RepairHistory::with(['item', 'pelapor', 'penangan', 'item.lokasi', 'item.kategori'])
            ->when($this->filterLokasi, function ($q) {
                $q->whereHas('item', fn($q) => $q->where('lokasi_id', $this->filterLokasi));
            })
            ->when($this->filterKategori, function ($q) {
                $q->whereHas('item', fn($q) => $q->where('kategori_id', $this->filterKategori));
            })
            ->when($this->filterTanggalDari, fn($q) => $q->whereDate('tanggal_laporan', '>=', $this->filterTanggalDari))
            ->when($this->filterTanggalSampai, fn($q) => $q->whereDate('tanggal_laporan', '<=', $this->filterTanggalSampai))
            ->orderByDesc('tanggal_laporan')
            ->get();

        return compact('riwayats');
    }

    public function render()
    {
        $this->authorize('laporan.view');


        $data = $this->getReportData();
        $kategoriOptions = Category::where('is_active', true)->orderBy('nama')->get();
        $lokasiOptions = Location::where('is_active', true)->orderBy('nama')->get();

        return view('livewire.laporan.laporan-index', array_merge($data, [
            'kategoriOptions' => $kategoriOptions,
            'lokasiOptions' => $lokasiOptions,
        ]));
    }
}

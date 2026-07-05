<?php

namespace App\Livewire\Barang;

use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BarangImport;

#[Layout('layouts.app')]
#[Title('Daftar Barang')]
class BarangList extends Component
{
    use WithPagination, WithFileUploads;

    #[Url(as: 'cari', history: true)]
    public string $search = '';

    public string $sortField = 'kode_aset';
    public string $sortDirection = 'asc';

    #[Url(as: 'kondisi', history: true)]
    public string $filterKondisi = '';

    #[Url(as: 'status', history: true)]
    public string $filterStatus = '';

    #[Url(as: 'kategori', history: true)]
    public string $filterKategori = '';

    #[Url(as: 'lokasi', history: true)]
    public string $filterLokasi = '';

    #[Url(as: 'dari', history: true)]
    public string $filterTanggalDari = '';

    #[Url(as: 'sampai', history: true)]
    public string $filterTanggalSampai = '';

    public ?int $deleteId = null;
    public bool $deleteModal = false;

    public bool $importModal = false;
    public $importFile = null;
    public bool $importProcessing = false;
    public ?array $importResult = null;

    public array $kondisiOptions = [
        'Baik', 'Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan', 'Sudah Diperbaiki', 'Afkir-Dihapuskan'
    ];

    public array $statusOptions = [
        'Digunakan', 'Idle', 'Dipinjam', 'Dalam Perbaikan', 'Menunggu Pembuangan'
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterKondisi(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFilterKategori(): void
    {
        $this->resetPage();
    }

    public function updatingFilterLokasi(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTanggalDari(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTanggalSampai(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['filterKondisi', 'filterStatus', 'filterKategori', 'filterLokasi', 'filterTanggalDari', 'filterTanggalSampai', 'search']);
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('barang.delete');
        $this->deleteId = $id;
        $this->deleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->deleteId = null;
        $this->deleteModal = false;
    }

    public function delete(): void
    {
        $this->authorize('barang.delete');

        $item = Item::findOrFail($this->deleteId);
        $itemData = $item->toArray();
        $item->delete();

        activity('barang')
            ->causedBy(auth()->user())
            ->performedOn($item)
            ->event('deleted')
            ->withProperties([
                'data' => $itemData,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log("Menghapus barang {$item->nama} ({$item->kode_aset}).");

        session()->flash('success', 'Barang berhasil dihapus.');
        $this->cancelDelete();
    }

    public function openImportModal(): void
    {
        $this->resetImportState();
        $this->importModal = true;
    }

    public function closeImportModal(): void
    {
        $this->resetImportState();
        $this->importModal = false;
    }

    public function resetImportState(): void
    {
        $this->importFile = null;
        $this->importProcessing = false;
        $this->importResult = null;
    }

    public function updatedImportFile(): void
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ]);
    }

    public function startImport(): void
    {
        if (!$this->importFile) {
            return;
        }

        $this->importProcessing = true;

        try {
            $import = new BarangImport(auth()->id());
            Excel::import($import, $this->importFile);

            $importedCount = $import->getImportedCount();

            activity('barang')
                ->causedBy(auth()->user())
                ->event('imported')
                ->withProperties([
                    'imported_count' => $importedCount,
                    'errors' => $import->getErrors(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Mengimpor {$importedCount} barang dari file Excel.");

            $this->importResult = [
                'success' => true,
                'message' => "Import selesai: {$importedCount} baris berhasil diimpor.",
                'errors' => $import->getErrors(),
                'imported' => $importedCount,
            ];
        } catch (\Exception $e) {
            activity('barang')
                ->causedBy(auth()->user())
                ->event('imported')
                ->withProperties([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Gagal mengimpor barang: {$e->getMessage()}.");

            $this->importResult = [
                'success' => false,
                'message' => 'Gagal mengimpor file: ' . $e->getMessage(),
                'errors' => [],
                'imported' => 0,
            ];
        } finally {
            $this->importProcessing = false;
        }
    }

    public function render()
    {
        $items = Item::query()
            ->with(['kategori', 'lokasi', 'creator'])
            ->withCount('children', 'repairHistories')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_aset', 'like', '%' . $this->search . '%')
                        ->orWhere('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('nomor_seri', 'like', '%' . $this->search . '%')
                        ->orWhere('vendor', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterKondisi, fn($q) => $q->where('kondisi', $this->filterKondisi))
            ->when($this->filterStatus, fn($q) => $q->where('status_penggunaan', $this->filterStatus))
            ->when($this->filterKategori, fn($q) => $q->where('kategori_id', $this->filterKategori))
            ->when($this->filterLokasi, fn($q) => $q->where('lokasi_id', $this->filterLokasi))
            ->when($this->filterTanggalDari, fn($q) => $q->whereDate('tanggal_pengadaan', '>=', $this->filterTanggalDari))
            ->when($this->filterTanggalSampai, fn($q) => $q->whereDate('tanggal_pengadaan', '<=', $this->filterTanggalSampai))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $kategoris = Category::where('is_active', true)->orderBy('nama')->get();
        $lokasis = Location::where('is_active', true)->orderBy('nama')->get();

        return view('livewire.barang.barang-list', compact('items', 'kategoris', 'lokasis'));
    }
}

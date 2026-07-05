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

#[Layout('layouts.app')]
#[Title('Daftar Barang')]
class BarangList extends Component
{
    use WithPagination;

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
        $item->delete();

        session()->flash('success', 'Barang berhasil dihapus.');
        $this->cancelDelete();
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

<?php

namespace App\Livewire\Stok;

use App\Models\Stock;
use App\Models\Category;
use App\Models\Location;
use App\Models\StockMovement;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

#[Layout('layouts.app')]
#[Title('Daftar Stok Gudang')]
class StokList extends Component
{
    use WithPagination;

    #[Url(as: 'cari', history: true)]
    public $search = '';

    #[Url(as: 'kategori', history: true)]
    public $filterKategori = '';

    #[Url(as: 'lokasi', history: true)]
    public $filterLokasi = '';

    public $filterLowStock = false;

    public $sortField = 'nama';
    public $sortDirection = 'asc';

    public $deleteId = null;
    public $deleteModal = false;

    public $showMovements = false;
    public $movementStock = null;
    public $movements = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterKategori()
    {
        $this->resetPage();
    }

    public function updatingFilterLokasi()
    {
        $this->resetPage();
    }

    public function updatingFilterLowStock()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->authorize('stok.delete');

        $this->deleteId = $id;
        $this->deleteModal = true;
    }

    public function cancelDelete()
    {
        $this->deleteId = null;
        $this->deleteModal = false;
    }

    public function delete()
    {
        $this->authorize('stok.delete');

        $stock = Stock::withCount('movements')->findOrFail($this->deleteId);

        if ($stock->movements_count > 0) {
            session()->flash('error', 'Stok memiliki riwayat mutasi, tidak dapat dihapus.');
            $this->cancelDelete();
            return;
        }

        $stockName = $stock->nama;
        $stock->delete();

        activity('stok')
            ->causedBy(auth()->user())
            ->performedOn($stock)
            ->event('deleted')
            ->withProperties([
                'data' => $stock->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log("Menghapus stok {$stockName}.");

        session()->flash('success', 'Stok berhasil dihapus.');
        $this->cancelDelete();
    }

    public function viewMovements($id)
    {
        $this->movementStock = Stock::with('kategori', 'lokasi')->findOrFail($id);
        $this->movements = StockMovement::with('creator')
            ->where('stock_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        $this->showMovements = true;
    }

    public function closeMovements()
    {
        $this->showMovements = false;
        $this->movementStock = null;
        $this->movements = [];
    }

    public function render()
    {
        $stoks = Stock::query()
            ->with('kategori', 'lokasi', 'itemTemplate')
            ->withCount('movements')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('vendor', 'like', '%' . $this->search . '%')
                        ->orWhere('satuan', 'like', '%' . $this->search . '%')
                        ->orWhere('catatan', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterKategori, function ($query) {
                $query->where('kategori_id', $this->filterKategori);
            })
            ->when($this->filterLokasi, function ($query) {
                $query->where('lokasi_id', $this->filterLokasi);
            })
            ->when($this->filterLowStock, function ($query) {
                $query->whereColumn('jumlah_stok', '<=', 'ambang_batas_minimum');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $categories = Category::where('is_active', true)->orderBy('nama')->get();
        $locations = Location::where('is_active', true)->orderBy('nama')->get();

        return view('livewire.stok.stok-list', compact('stoks', 'categories', 'locations'));
    }
}

<?php

namespace App\Livewire\Lokasi;

use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

#[Layout('layouts.app')]
#[Title('Daftar Lokasi')]
class LokasiList extends Component
{
    use WithPagination;

    #[Url(as: 'cari', history: true)]
    public $search = '';

    public $sortField = 'kode_lokasi';
    public $sortDirection = 'asc';

    public $deleteId = null;
    public $deleteModal = false;

    public function updatingSearch()
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
        $this->authorize('lokasi.delete');

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
        $this->authorize('lokasi.delete');

        $location = Location::withCount('children', 'items', 'stocks')->findOrFail($this->deleteId);

        if ($location->children_count > 0) {
            session()->flash('error', 'Lokasi memiliki sub-lokasi, tidak dapat dihapus.');
            $this->cancelDelete();
            return;
        }

        if ($location->items_count > 0 || $location->stocks_count > 0) {
            session()->flash('error', 'Lokasi masih memiliki barang/aset, tidak dapat dihapus.');
            $this->cancelDelete();
            return;
        }

        $location->delete();

        session()->flash('success', 'Lokasi berhasil dihapus.');
        $this->cancelDelete();
    }

    #[On('toggle-status')]
    public function toggleStatus($id)
    {
        $this->authorize('lokasi.edit');

        $location = Location::findOrFail($id);
        $location->update(['is_active' => !$location->is_active]);

        session()->flash('success', 'Status lokasi berhasil diperbarui.');
    }

    public function render()
    {
        $lokasis = Location::query()
            ->with('parent', 'penanggungJawab')
            ->withCount('children', 'items')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_lokasi', 'like', '%' . $this->search . '%')
                        ->orWhere('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('tipe_lokasi', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.lokasi.lokasi-list', compact('lokasis'));
    }
}

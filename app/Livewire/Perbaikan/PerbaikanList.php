<?php

namespace App\Livewire\Perbaikan;

use App\Models\RepairHistory;
use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Riwayat Perbaikan')]
class PerbaikanList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public string $sortField = 'tanggal_laporan';

    public string $sortDirection = 'desc';

    public ?int $deleteId = null;

    public bool $deleteModal = false;

    protected $queryString = ['search', 'statusFilter', 'dateFrom', 'dateTo', 'sortField', 'sortDirection'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
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

    public function confirmDelete(int $id): void
    {
        $this->authorize('perbaikan.delete');

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
        $this->authorize('perbaikan.delete');

        $repair = RepairHistory::findOrFail($this->deleteId);
        $repairId = $repair->id;
        $repair->delete();

        activity('perbaikan')
            ->causedBy(auth()->user())
            ->performedOn($repair)
            ->event('deleted')
            ->withProperties([
                'data' => $repair->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log("Menghapus riwayat perbaikan #{$repairId}.");

        session()->flash('success', 'Riwayat perbaikan berhasil dihapus.');
        $this->cancelDelete();
    }

    #[On('confirm-delete')]
    public function deleteFromEvent(int $id): void
    {
        $this->authorize('perbaikan.delete');

        $repair = RepairHistory::find($id);
        if (!$repair) {
            session()->flash('error', 'Riwayat perbaikan tidak ditemukan.');
            return;
        }

        $repairData = $repair->toArray();

        if ($repair->trashed()) {
            $repair->forceDelete();

            activity('perbaikan')
                ->causedBy(auth()->user())
                ->event('deleted')
                ->withProperties([
                    'data' => $repairData,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Menghapus permanen riwayat perbaikan #{$id}.");

            session()->flash('success', 'Riwayat perbaikan berhasil dihapus permanen.');
        } else {
            $repair->delete();

            activity('perbaikan')
                ->causedBy(auth()->user())
                ->performedOn($repair)
                ->event('deleted')
                ->withProperties([
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Menghapus riwayat perbaikan #{$id}.");

            session()->flash('success', 'Riwayat perbaikan berhasil dihapus.');
        }
    }

    public function restore(int $id): void
    {
        $this->authorize('perbaikan.delete');

        $repair = RepairHistory::onlyTrashed()->find($id);
        if ($repair) {
            $repair->restore();

            activity('perbaikan')
                ->causedBy(auth()->user())
                ->performedOn($repair)
                ->event('updated')
                ->withProperties([
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Memulihkan riwayat perbaikan #{$id}.");

            session()->flash('success', 'Riwayat perbaikan berhasil dipulihkan.');
        }
    }

    public function render()
    {
        $batasHari = (int) Setting::getValue('perbaikan.batas_hari', 7);

        $repairs = RepairHistory::query()
            ->with(['item', 'pelapor', 'penangan', 'creator'])
            ->withTrashed()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('item', function ($q2) {
                        $q2->where('kode_aset', 'like', '%' . $this->search . '%')
                            ->orWhere('nama', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('deskripsi_kerusakan', 'like', '%' . $this->search . '%')
                    ->orWhere('tindakan', 'like', '%' . $this->search . '%')
                    ->orWhere('vendor_eksternal', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter === 'pending', function ($query) {
                $query->whereNull('tanggal_selesai')->whereNull('status_akhir');
            })
            ->when($this->statusFilter === 'done', function ($query) {
                $query->whereNotNull('tanggal_selesai')->whereNotNull('status_akhir');
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('tanggal_laporan', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('tanggal_laporan', '<=', $this->dateTo);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.perbaikan.perbaikan-list', compact('repairs', 'batasHari'));
    }
}

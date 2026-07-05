<?php

namespace App\Livewire\LogActivity;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

#[Layout('layouts.app')]
#[Title('Log Aktivitas')]
class LogActivityList extends Component
{
    use WithPagination;

    #[Url(as: 'cari', history: true)]
    public string $search = '';

    #[Url(as: 'log', history: true)]
    public string $filterLogName = '';

    #[Url(as: 'event', history: true)]
    public string $filterEvent = '';

    #[Url(as: 'dari', history: true)]
    public string $filterTanggalDari = '';

    #[Url(as: 'sampai', history: true)]
    public string $filterTanggalSampai = '';

    #[Url(as: 'oleh', history: true)]
    public string $filterCauser = '';

    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public ?int $detailId = null;
    public bool $showDetail = false;
    public ?Activity $detailActivity = null;

    public array $logNameOptions = [];
    public array $eventOptions = [];

    public function mount(): void
    {
        $this->authorize('log-aktivitas.view');

        $this->logNameOptions = Activity::query()
            ->whereNotNull('log_name')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name')
            ->toArray();

        $this->eventOptions = Activity::query()
            ->whereNotNull('event')
            ->distinct()
            ->orderBy('event')
            ->pluck('event')
            ->toArray();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterLogName(): void
    {
        $this->resetPage();
    }

    public function updatingFilterEvent(): void
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

    public function updatingFilterCauser(): void
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
        $this->reset(['search', 'filterLogName', 'filterEvent', 'filterTanggalDari', 'filterTanggalSampai', 'filterCauser']);
        $this->resetPage();
    }

    public function viewDetail(int $id): void
    {
        $this->detailActivity = Activity::with('causer', 'subject')->findOrFail($id);
        $this->detailId = $id;
        $this->showDetail = true;
    }

    public function closeDetail(): void
    {
        $this->detailId = null;
        $this->detailActivity = null;
        $this->showDetail = false;
    }

    public function getEventBadgeClass(?string $event): string
    {
        return match ($event) {
            'created' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
            'updated' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
            'deleted' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
            'viewed' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-300',
            'imported' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300',
            'exported' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300',
            'login' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/50 dark:text-cyan-300',
            'logout' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-300',
            'toggled' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-300',
        };
    }

    public function getLogNameLabel(?string $logName): string
    {
        if (!$logName) return '-';
        return match ($logName) {
            'barang' => 'Barang / Aset',
            'lokasi' => 'Lokasi',
            'kategori' => 'Kategori',
            'template' => 'Template Barang',
            'stok' => 'Stok Gudang',
            'mutasi' => 'Mutasi Stok',
            'perbaikan' => 'Perbaikan',
            'pengguna' => 'Pengguna',
            'pengaturan' => 'Pengaturan',
            'laporan' => 'Laporan',
            'auth' => 'Autentikasi',
            'dashboard' => 'Dashboard',
            default => ucfirst($logName ?? ''),
        };
    }

    public function getEventLabel(?string $event): string
    {
        return match ($event) {
            'created' => 'Membuat',
            'updated' => 'Mengubah',
            'deleted' => 'Menghapus',
            'viewed' => 'Melihat',
            'imported' => 'Mengimpor',
            'exported' => 'Mengekspor',
            'login' => 'Masuk',
            'logout' => 'Keluar',
            'toggled' => 'Mengubah Status',
            default => $event ?? '-',
        };
    }

    public function render()
    {
        $activities = Activity::query()
            ->with('causer')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('description', 'like', '%' . $this->search . '%')
                        ->orWhereHas('causer', function ($q2) {
                            $q2->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterLogName, fn($q) => $q->where('log_name', $this->filterLogName))
            ->when($this->filterEvent, fn($q) => $q->where('event', $this->filterEvent))
            ->when($this->filterTanggalDari, fn($q) => $q->whereDate('created_at', '>=', $this->filterTanggalDari))
            ->when($this->filterTanggalSampai, fn($q) => $q->whereDate('created_at', '<=', $this->filterTanggalSampai))
            ->when($this->filterCauser, function ($q) {
                $q->whereHas('causer', fn($q2) => $q2->where('name', 'like', '%' . $this->filterCauser . '%'));
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $causerList = Activity::query()
            ->whereHas('causer')
            ->with('causer')
            ->distinct('causer_id')
            ->select('causer_id', 'causer_type')
            ->get()
            ->pluck('causer.name', 'causer.name')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        return view('livewire.log-activity.log-activity-list', compact('activities', 'causerList'));
    }
}

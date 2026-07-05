<?php

namespace App\Livewire\Perbaikan;

use App\Models\RepairHistory;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PerbaikanShow extends Component
{
    public RepairHistory $repair;

    public function mount(RepairHistory $repair): void
    {
        $this->repair = $repair->load(['item', 'item.lokasi', 'item.kategori', 'pelapor', 'penangan', 'stock', 'creator']);

        $itemLabel = $this->repair->item ? "{$this->repair->item->nama} ({$this->repair->item->kode_aset})" : "#{$repair->id}";

        activity('perbaikan')
            ->causedBy(auth()->user())
            ->performedOn($this->repair)
            ->event('viewed')
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log("Melihat detail perbaikan {$itemLabel}.");
    }

    public function render()
    {
        return view('livewire.perbaikan.perbaikan-show')
            ->title('Detail Perbaikan: ' . ($this->repair->item?->nama ?? 'N/A'));
    }
}

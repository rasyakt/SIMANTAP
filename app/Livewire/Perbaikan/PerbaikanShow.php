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
    }

    public function render()
    {
        return view('livewire.perbaikan.perbaikan-show')
            ->title('Detail Perbaikan: ' . ($this->repair->item?->nama ?? 'N/A'));
    }
}

<?php

namespace App\Livewire\Stok;

use App\Models\Stock;
use App\Models\StockMovement;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StokShow extends Component
{
    public Stock $stock;

    public function mount(Stock $stock): void
    {
        $this->stock = $stock->load(['kategori', 'lokasi', 'itemTemplate', 'movements' => function ($q) {
            $q->with('creator', 'fromLocation', 'toLocation')->latest();
        }]);

        activity('stok')
            ->causedBy(auth()->user())
            ->performedOn($this->stock)
            ->event('viewed')
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log("Melihat detail stok {$this->stock->nama}.");
    }

    public function render()
    {
        return view('livewire.stok.stok-show')
            ->title('Detail Stok: ' . $this->stock->nama);
    }
}

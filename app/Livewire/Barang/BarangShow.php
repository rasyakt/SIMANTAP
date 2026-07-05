<?php

namespace App\Livewire\Barang;

use App\Models\Item;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
class BarangShow extends Component
{
    public Item $item;

    public function mount(Item $item): void
    {
        $this->item = $item->load([
            'kategori', 'lokasi', 'itemTemplate', 'parent', 'creator', 'updater',
            'children' => fn($q) => $q->with('kategori', 'lokasi'),
            'components.componentItem',
            'statusHistories.creator',
            'repairHistories' => fn($q) => $q->latest(),
        ]);
    }

    public function render()
    {
        return view('livewire.barang.barang-show')
            ->title('Detail Barang: ' . $this->item->nama);
    }
}

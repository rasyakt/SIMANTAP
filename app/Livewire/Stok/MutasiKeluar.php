<?php

namespace App\Livewire\Stok;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
class MutasiKeluar extends MutasiForm
{
    public function mount($id = null, $tipe = null)
    {
        $this->tipe = 'keluar';
        parent::mount($id, $tipe);
    }

    #[Title('Stok Keluar')]
    public function render()
    {
        $stocks = \App\Models\Stock::with('kategori', 'lokasi')->orderBy('nama')->get();
        $items = \App\Models\Item::orderBy('nama')->get();
        $locations = \App\Models\Location::where('is_active', true)->orderBy('nama')->get();

        return view('livewire.stok.mutasi-form', compact('stocks', 'items', 'locations'));
    }
}

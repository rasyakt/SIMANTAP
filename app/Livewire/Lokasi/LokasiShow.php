<?php

namespace App\Livewire\Lokasi;

use App\Models\Location;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class LokasiShow extends Component
{
    public Location $lokasi;

    public function mount(Location $lokasi): void
    {
        $this->lokasi = $lokasi->load(['parent', 'penanggungJawab', 'children', 'items' => function ($q) {
            $q->with('kategori')->limit(20);
        }]);
    }

    public function render()
    {
        return view('livewire.lokasi.lokasi-show')
            ->title('Detail Lokasi: ' . $this->lokasi->nama);
    }
}

<?php

namespace App\Livewire\Kategori;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class KategoriShow extends Component
{
    public Category $kategori;

    public function mount(Category $kategori): void
    {
        $this->kategori = $kategori->load(['parent', 'children', 'items' => function ($q) {
            $q->with('lokasi')->limit(20);
        }]);
    }

    public function render()
    {
        return view('livewire.kategori.kategori-show')
            ->title('Detail Kategori: ' . $this->kategori->nama);
    }
}

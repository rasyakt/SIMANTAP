<?php

namespace App\Livewire\Template;

use App\Models\ItemTemplate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Template Barang')]
class TemplateList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public function updatingSearch(): void
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

    #[On('template-saved')]
    #[On('template-deleted')]
    public function refresh(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        if (!auth()->user()->can('template.delete')) {
            $this->dispatch('alert', type: 'error', message: 'Anda tidak memiliki izin untuk menghapus template.');
            return;
        }

        $template = ItemTemplate::findOrFail($id);
        $template->delete();

        $this->dispatch('template-deleted');
        $this->dispatch('alert', type: 'success', message: 'Template berhasil dihapus.');
    }

    public function render()
    {
        $templates = ItemTemplate::with('kategori')
            ->when($this->search, function ($query) {
                $query->whereAny([
                    'nama',
                    'merk',
                    'tipe_model',
                    'spesifikasi',
                ], 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.template.template-list', compact('templates'));
    }
}

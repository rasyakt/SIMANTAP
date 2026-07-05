<?php

namespace App\Livewire\Kategori;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Daftar Kategori')]
class KategoriList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $sortField = 'nama';

    public string $sortDirection = 'asc';

    public ?int $kategoriId = null;

    protected $queryString = ['search', 'sortField', 'sortDirection'];

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

    #[On('confirm-delete')]
    public function delete(int $id): void
    {
        if (!auth()->user()->can('kategori.delete')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus kategori.');
            return;
        }

        $category = Category::withTrashed()->find($id);

        if (!$category) {
            session()->flash('error', 'Kategori tidak ditemukan.');
            return;
        }

        if ($category->children()->count() > 0) {
            session()->flash('error', 'Kategori tidak dapat dihapus karena masih memiliki sub kategori.');
            return;
        }

        if ($category->trashed()) {
            $category->forceDelete();
            session()->flash('success', 'Kategori berhasil dihapus permanen.');
        } else {
            $category->delete();
            session()->flash('success', 'Kategori berhasil dihapus.');
        }
    }

    public function restore(int $id): void
    {
        if (!auth()->user()->can('kategori.delete')) {
            session()->flash('error', 'Anda tidak memiliki izin untuk memulihkan kategori.');
            return;
        }

        $category = Category::onlyTrashed()->find($id);

        if ($category) {
            $category->restore();
            session()->flash('success', 'Kategori berhasil dipulihkan.');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->with('parent')
            ->paginate(10);

        return view('livewire.kategori.kategori-list', compact('categories'));
    }
}

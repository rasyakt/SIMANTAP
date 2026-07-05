<?php

namespace App\Livewire\Pengguna;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Daftar Pengguna')]
class PenggunaList extends Component
{
    use WithPagination;

    #[Url(as: 'cari', history: true)]
    public string $search = '';

    #[Url(as: 'role', history: true)]
    public string $filterRole = '';

    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    public ?int $deleteId = null;
    public bool $deleteModal = false;
    public string $deleteName = '';

    public function updatingSearch()
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

    public function toggleActive(int $id): void
    {
        $this->authorize('pengguna.edit');

        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        session()->flash('success', 'Status pengguna berhasil diperbarui.');
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('pengguna.delete');

        $user = User::findOrFail($id);
        $this->deleteId = $user->id;
        $this->deleteName = $user->name;
        $this->deleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->deleteId = null;
        $this->deleteName = '';
        $this->deleteModal = false;
    }

    public function delete(): void
    {
        $this->authorize('pengguna.delete');

        $user = User::findOrFail($this->deleteId);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun sendiri.');
            $this->cancelDelete();
            return;
        }

        $user->delete();
        session()->flash('success', 'Pengguna berhasil dihapus.');
        $this->cancelDelete();
    }

    public function render()
    {
        $users = User::query()
            ->with('roles')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRole, fn($q) => $q->role($this->filterRole))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $roles = Role::orderBy('name')->get();

        return view('livewire.pengguna.pengguna-list', compact('users', 'roles'));
    }
}

<?php

namespace App\Livewire\Pengguna;

use App\Models\Location;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Role;

class PenggunaForm extends Component
{
    public ?User $user = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $password = '';

    public bool $is_active = true;

    public array $selectedRoles = [];

    public array $selectedLocations = [];

    #[Layout('layouts.app')]
    public function mount(?User $user = null): void
    {
        $this->user = $user;

        if ($user && $user->exists) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone ?? '';
            $this->is_active = $user->is_active;
            $this->selectedRoles = $user->roles->pluck('id')->map(fn($id) => (string) $id)->toArray();
            $this->selectedLocations = $user->locations->pluck('id')->map(fn($id) => (string) $id)->toArray();
        }
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . ($this->user?->id ?? 'NULL'),
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,id',
            'selectedLocations' => 'nullable|array',
            'selectedLocations.*' => 'exists:locations,id',
        ];

        if (!$this->user || !$this->user->exists) {
            $rules['password'] = 'required|string|min:8';
        }

        return $rules;
    }

    public function save(): void
    {
        if ($this->user && $this->user->exists) {
            $this->authorize('pengguna.edit');
        } else {
            $this->authorize('pengguna.create');
        }

        $validated = $this->validate();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => $validated['is_active'],
        ];

        if ($this->user && $this->user->exists) {
            $this->user->update($data);
            $this->user->syncRoles($this->selectedRoles);
            $this->user->locations()->sync($this->selectedLocations);
            session()->flash('success', 'Pengguna berhasil diperbarui.');
        } else {
            $data['password'] = $validated['password'];
            $newUser = User::create($data);
            $newUser->syncRoles($this->selectedRoles);
            $newUser->locations()->sync($this->selectedLocations);
            session()->flash('success', 'Pengguna berhasil ditambahkan.');
        }

        $this->redirectRoute('pengguna.index', navigate: true);
    }

    public function render()
    {
        $roles = Role::orderBy('name')->get();
        $locations = Location::where('is_active', true)->orderBy('nama')->get();

        return view('livewire.pengguna.pengguna-form', compact('roles', 'locations'))
            ->title($this->user?->exists ? 'Edit Pengguna' : 'Tambah Pengguna');
    }
}

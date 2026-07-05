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
            // Mencegah non-Super Admin mengedit akun Super Admin
            /** @var \App\Models\User|null $currentUser */
            $currentUser = \Illuminate\Support\Facades\Auth::user();
            if ($user->hasRole('Super Admin') && (!$currentUser || !$currentUser->hasRole('Super Admin'))) {
                abort(403, 'Anda tidak memiliki hak untuk mengedit akun Super Admin.');
            }

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

        // 1. Dapatkan role Super Admin
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        /** @var \App\Models\User|null $currentUser */
        $currentUser = \Illuminate\Support\Facades\Auth::user();
        $isCurrentUserSuperAdmin = $currentUser && $currentUser->hasRole('Super Admin');

        // 2. Batasan untuk non-Super Admin
        if ($superAdminRole) {
            $isAssigningSuperAdmin = in_array((string)$superAdminRole->id, $this->selectedRoles);
            $isEditingSuperAdmin = $this->user && $this->user->exists && $this->user->hasRole('Super Admin');

            if (($isAssigningSuperAdmin || $isEditingSuperAdmin) && !$isCurrentUserSuperAdmin) {
                session()->flash('error', 'Hanya pengguna dengan role Super Admin yang dapat membuat atau mengelola akun Super Admin.');
                return;
            }
        }

        // 2.5. Batasan Maksimal 1 Super Admin di Sistem
        if ($superAdminRole && in_array((string)$superAdminRole->id, $this->selectedRoles)) {
            $anotherSuperAdmin = User::role('Super Admin')
                ->when($this->user && $this->user->exists, function ($query) {
                    $query->where('id', '!=', $this->user->id);
                })
                ->first();

            if ($anotherSuperAdmin) {
                session()->flash('error', 'Sistem hanya diperbolehkan memiliki maksimal satu akun dengan role Super Admin.');
                return;
            }
        }

        // 3. Batasan saat mengedit diri sendiri
        $currentUserId = \Illuminate\Support\Facades\Auth::id();
        if ($this->user && $this->user->exists && $this->user->id === $currentUserId) {
            // Mencegah menonaktifkan diri sendiri
            if (!$validated['is_active']) {
                session()->flash('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
                return;
            }

            // Mencegah menghapus role Super Admin dari diri sendiri
            if ($superAdminRole && $this->user->hasRole('Super Admin') && !in_array((string)$superAdminRole->id, $this->selectedRoles)) {
                session()->flash('error', 'Anda tidak dapat menghapus role Super Admin dari akun Anda sendiri untuk mencegah terkunci.');
                return;
            }
        }

        // 4. Mencegah terkunci (harus ada minimal satu Super Admin yang aktif di sistem)
        if ($superAdminRole) {
            $isTargetSuperAdmin = $this->user && $this->user->exists && $this->user->hasRole('Super Admin');
            $willNoLongerBeSuperAdmin = !in_array((string)$superAdminRole->id, $this->selectedRoles);

            if ($isTargetSuperAdmin && ($willNoLongerBeSuperAdmin || !$validated['is_active'])) {
                $otherActiveSuperAdmins = User::role('Super Admin')
                    ->where('is_active', true)
                    ->where('id', '!=', $this->user->id)
                    ->count();

                if ($otherActiveSuperAdmins === 0) {
                    session()->flash('error', 'Tidak dapat menonaktifkan atau mengubah role. Sistem harus memiliki minimal satu Super Admin yang aktif.');
                    return;
                }
            }
        }

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

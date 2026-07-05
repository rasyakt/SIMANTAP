<?php

namespace App\Livewire\Pengguna;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PenggunaShow extends Component
{
    public User $user;

    public function mount(User $user): void
    {
        $this->user = $user->load(['roles', 'locations']);
    }

    public function render()
    {
        return view('livewire.pengguna.pengguna-show')
            ->title('Detail Pengguna: ' . $this->user->name);
    }
}

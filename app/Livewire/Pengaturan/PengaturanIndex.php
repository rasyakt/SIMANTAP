<?php

namespace App\Livewire\Pengaturan;

use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Pengaturan Aplikasi')]
class PengaturanIndex extends Component
{
    public array $settings = [];

    public array $groups = [];

    public bool $editing = false;
    public string $editKey = '';
    public string $editValue = '';

    public function mount(): void
    {
        $this->loadSettings();
    }

    public function loadSettings(): void
    {
        $allSettings = Setting::orderBy('group')->orderBy('key')->get();
        $this->settings = $allSettings->groupBy('group')->toArray();
        $this->groups = $allSettings->pluck('group')->unique()->values()->toArray();
    }

    public function startEdit(string $key): void
    {
        $this->authorize('pengaturan.edit');

        $this->editing = true;
        $this->editKey = $key;
        $this->editValue = Setting::getValue($key, '');
    }

    public function cancelEdit(): void
    {
        $this->editing = false;
        $this->editKey = '';
        $this->editValue = '';
    }

    public function saveSetting(): void
    {
        $this->authorize('pengaturan.edit');

        $this->validate([
            'editValue' => 'required|string|max:500',
        ]);

        $oldValue = Setting::getValue($this->editKey, '');
        Setting::setValue($this->editKey, $this->editValue);

        activity('pengaturan')
            ->causedBy(\Illuminate\Support\Facades\Auth::user())
            ->event('updated')
            ->withProperties([
                'key' => $this->editKey,
                'old_value' => $oldValue,
                'new_value' => $this->editValue,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log("Mengubah pengaturan {$this->editKey}.");

        session()->flash('success', 'Pengaturan "' . $this->editKey . '" berhasil diperbarui.');

        $this->cancelEdit();
        $this->loadSettings();
    }

    public function getGroupLabel(string $group): string
    {
        return match ($group) {
            'general' => 'Umum',
            'notifikasi' => 'Notifikasi',
            'aset' => 'Aset',
            default => ucfirst($group),
        };
    }

    public function getSettingLabel(string $key): string
    {
        return match ($key) {
            'nama_instansi' => 'Nama Instansi',
            'alamat' => 'Alamat',
            'kota' => 'Kota',
            'provinsi' => 'Provinsi',
            'nomor_telp' => 'Nomor Telepon',
            'email' => 'Email',
            'website' => 'Website',
            'ambang_stok_default' => 'Ambang Stok Default',
            'hari_tenggang_perbaikan' => 'Hari Tenggang Perbaikan',
            'format_kode' => 'Format Kode Aset',
            'prefix_auto' => 'Prefix Otomatis',
            default => ucwords(str_replace('_', ' ', $key)),
        };
    }

    public function render()
    {
        $settingsGrouped = Setting::orderBy('key')->get()->groupBy('group');

        return view('livewire.pengaturan.pengaturan-index', compact('settingsGrouped'));
    }
}

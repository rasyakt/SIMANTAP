<?php

namespace App\Livewire\Lokasi;

use App\Models\Location;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
class LokasiForm extends Component
{
    public $locationId = null;

    public $kode_lokasi = '';

    public $nama = '';

    public $tipe_lokasi = '';

    public $parent_id = '';

    public $penanggung_jawab_id = '';

    public $kapasitas = '';

    public $deskripsi = '';

    public $is_active = true;

    public $tipeOptions = [
        'lab' => 'Laboratorium',
        'gudang' => 'Gudang',
        'kantor' => 'Kantor',
        'ruang_kelas' => 'Ruang Kelas',
        'lainnya' => 'Lainnya',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $location = Location::findOrFail($id);

            $this->locationId = $location->id;
            $this->kode_lokasi = $location->kode_lokasi;
            $this->nama = $location->nama;
            $this->tipe_lokasi = $location->tipe_lokasi;
            $this->parent_id = (string) $location->parent_id;
            $this->penanggung_jawab_id = (string) $location->penanggung_jawab_id;
            $this->kapasitas = $location->kapasitas;
            $this->deskripsi = $location->deskripsi;
            $this->is_active = $location->is_active;
        }
    }

    public function rules()
    {
        $rules = [
            'kode_lokasi' => 'required|string|max:20|unique:locations,kode_lokasi,' . $this->locationId,
            'nama' => 'required|string|max:255',
            'tipe_lokasi' => 'required|in:lab,gudang,kantor,ruang_kelas,lainnya',
            'parent_id' => 'nullable|exists:locations,id',
            'penanggung_jawab_id' => 'nullable|exists:users,id',
            'kapasitas' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ];

        if ($this->locationId) {
            $rules['parent_id'] = 'nullable|exists:locations,id|not_in:' . $this->locationId;
        }

        return $rules;
    }

    public function save()
    {
        if ($this->locationId) {
            $this->authorize('lokasi.edit');
        } else {
            $this->authorize('lokasi.create');
        }

        $this->validate();

        $data = [
            'kode_lokasi' => $this->kode_lokasi,
            'nama' => $this->nama,
            'tipe_lokasi' => $this->tipe_lokasi,
            'parent_id' => $this->parent_id ?: null,
            'penanggung_jawab_id' => $this->penanggung_jawab_id ?: null,
            'kapasitas' => $this->kapasitas ?: null,
            'deskripsi' => $this->deskripsi,
            'is_active' => $this->is_active,
        ];

        $isUpdate = $this->locationId !== null;
        $oldData = $isUpdate ? Location::find($this->locationId)?->toArray() : null;

        $location = Location::updateOrCreate(
            ['id' => $this->locationId],
            $data
        );

        if ($isUpdate) {
            activity('lokasi')
                ->causedBy(auth()->user())
                ->performedOn($location)
                ->event('updated')
                ->withProperties([
                    'old' => $oldData,
                    'new' => $data,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Mengubah lokasi {$location->nama} ({$location->kode_lokasi}).");

            session()->flash('success', 'Lokasi berhasil diperbarui.');
        } else {
            activity('lokasi')
                ->causedBy(auth()->user())
                ->performedOn($location)
                ->event('created')
                ->withProperties([
                    'data' => $data,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Menambahkan lokasi baru {$location->nama} ({$location->kode_lokasi}).");

            session()->flash('success', 'Lokasi berhasil ditambahkan.');
        }

        return $this->redirect(route('lokasi.index'), navigate: true);
    }

    public function render()
    {
        $parentLocations = Location::query()
            ->where('is_active', true)
            ->when($this->locationId, fn($q) => $q->where('id', '!=', $this->locationId))
            ->orderBy('kode_lokasi')
            ->get();

        $penanggungJawabs = User::role(['Admin', 'Super Admin'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.lokasi.lokasi-form', compact('parentLocations', 'penanggungJawabs'))
            ->title($this->locationId ? 'Edit Lokasi' : 'Tambah Lokasi');
    }
}

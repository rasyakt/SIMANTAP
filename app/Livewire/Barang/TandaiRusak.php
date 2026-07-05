<?php

namespace App\Livewire\Barang;

use App\Models\Item;
use App\Models\ItemStatusHistory;
use App\Models\RepairHistory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Tandai Rusak Barang')]
class TandaiRusak extends Component
{
    public Item $item;

    public string $tingkat_kerusakan = 'Rusak Ringan';
    public string $deskripsi_kerusakan = '';
    public string $tindakan = '';
    public string $catatan = '';

    public array $tingkatKerusakanOptions = [
        'Rusak Ringan',
        'Rusak Berat',
        'Afkir-Dihapuskan',
    ];

    public function mount(Item $item): void
    {
        $this->item = $item;
    }

    public function save(): void
    {
        $this->authorize('barang.edit');

        $validated = $this->validate([
            'tingkat_kerusakan' => 'required|in:Rusak Ringan,Rusak Berat,Afkir-Dihapuskan',
            'deskripsi_kerusakan' => 'required|string|max:5000',
            'tindakan' => 'nullable|string|max:5000',
            'catatan' => 'nullable|string|max:5000',
        ]);

        $kondisiSebelumnya = $this->item->kondisi;

        $statusPenggunaanBaru = match ($this->tingkat_kerusakan) {
            'Rusak Ringan' => 'Idle',
            'Rusak Berat' => 'Dalam Perbaikan',
            'Afkir-Dihapuskan' => 'Menunggu Pembuangan',
            default => $this->item->status_penggunaan,
        };

        $this->item->update([
            'kondisi' => $this->tingkat_kerusakan,
            'status_penggunaan' => $statusPenggunaanBaru,
            'updated_by' => auth()->id(),
        ]);

        ItemStatusHistory::create([
            'item_id' => $this->item->id,
            'kondisi_sebelumnya' => $kondisiSebelumnya,
            'kondisi_baru' => $this->tingkat_kerusakan,
            'status_sebelumnya' => $this->item->getOriginal('status_penggunaan'),
            'status_baru' => $statusPenggunaanBaru,
            'keterangan' => $validated['deskripsi_kerusakan'],
            'created_by' => auth()->id(),
        ]);

        RepairHistory::create([
            'item_id' => $this->item->id,
            'tanggal_laporan' => now(),
            'dilaporkan_oleh' => auth()->id(),
            'deskripsi_kerusakan' => $validated['deskripsi_kerusakan'],
            'tingkat_kerusakan' => $this->tingkat_kerusakan,
            'tindakan' => $validated['tindakan'] ?? '',
            'catatan' => $validated['catatan'] ?? '',
            'created_by' => auth()->id(),
        ]);

        session()->flash('success', 'Barang berhasil ditandai sebagai ' . strtolower($this->tingkat_kerusakan) . '.');

        $this->redirectRoute('barang.show', $this->item, navigate: true);
    }

    public function render()
    {
        return view('livewire.barang.tandai-rusak');
    }
}

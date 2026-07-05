<?php

namespace App\Livewire\Perbaikan;

use App\Models\Item;
use App\Models\ItemStatusHistory;
use App\Models\RepairHistory;
use App\Models\Stock;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PerbaikanForm extends Component
{
    public ?int $repairId = null;

    public ?RepairHistory $repair = null;

    public $item_id = '';

    public $item_search = '';

    public $item_kode_aset = '';

    public $item_nama = '';

    public $tanggal_laporan = '';

    public $dilaporkan_oleh = '';

    public $deskripsi_kerusakan = '';

    public $tingkat_kerusakan = 'Ringan';

    public $tindakan = '';

    public $tindakan_detail = '';

    public $ditangani_oleh = '';

    public $vendor_eksternal = '';

    public $biaya = '';

    public $tanggal_selesai = '';

    public $status_akhir = '';

    public $stock_id = '';

    public $catatan = '';

    public $showItemDropdown = false;

    public function mount($id = null): void
    {
        if ($id) {
            $this->repair = RepairHistory::with('item')->findOrFail($id);
            $this->repairId = $this->repair->id;
            $this->item_id = (string) $this->repair->item_id;
            $this->item_search = $this->repair->item?->kode_aset . ' - ' . $this->repair->item?->nama;
            $this->item_kode_aset = $this->repair->item?->kode_aset ?? '';
            $this->item_nama = $this->repair->item?->nama ?? '';
            $this->tanggal_laporan = $this->repair->tanggal_laporan?->format('Y-m-d') ?? '';
            $this->dilaporkan_oleh = (string) $this->repair->dilaporkan_oleh;
            $this->deskripsi_kerusakan = $this->repair->deskripsi_kerusakan;
            $this->tingkat_kerusakan = $this->repair->tingkat_kerusakan;
            $this->tindakan = $this->repair->tindakan ?? '';
            $this->tindakan_detail = $this->repair->tindakan_detail ?? '';
            $this->ditangani_oleh = (string) $this->repair->ditangani_oleh;
            $this->vendor_eksternal = $this->repair->vendor_eksternal ?? '';
            $this->biaya = $this->repair->biaya ?? '';
            $this->tanggal_selesai = $this->repair->tanggal_selesai?->format('Y-m-d') ?? '';
            $this->status_akhir = $this->repair->status_akhir ?? '';
            $this->stock_id = (string) $this->repair->stock_id;
            $this->catatan = $this->repair->catatan ?? '';
        } else {
            $this->tanggal_laporan = now()->format('Y-m-d');
            $this->dilaporkan_oleh = (string) auth()->id();
        }
    }

    public function updatedItemSearch(): void
    {
        $this->showItemDropdown = strlen($this->item_search) >= 2;
        $this->item_id = '';
        $this->item_kode_aset = '';
        $this->item_nama = '';
    }

    public function selectItem(int $id): void
    {
        $item = Item::find($id);
        if ($item) {
            $this->item_id = (string) $item->id;
            $this->item_search = $item->kode_aset . ' - ' . $item->nama;
            $this->item_kode_aset = $item->kode_aset;
            $this->item_nama = $item->nama;
            $this->showItemDropdown = false;
        }
    }

    public function clearItem(): void
    {
        $this->item_id = '';
        $this->item_search = '';
        $this->item_kode_aset = '';
        $this->item_nama = '';
        $this->showItemDropdown = false;
    }

    public function rules(): array
    {
        return [
            'item_id' => 'required|exists:items,id',
            'tanggal_laporan' => 'required|date',
            'dilaporkan_oleh' => 'nullable|exists:users,id',
            'deskripsi_kerusakan' => 'required|string|max:5000',
            'tingkat_kerusakan' => 'required|in:Ringan,Sedang,Berat,Kritis',
            'tindakan' => 'nullable|string|max:255',
            'tindakan_detail' => 'nullable|string|max:10000',
            'ditangani_oleh' => 'nullable|exists:users,id',
            'vendor_eksternal' => 'nullable|string|max:255',
            'biaya' => 'nullable|numeric|min:0',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_laporan',
            'status_akhir' => 'nullable|string|max:100',
            'stock_id' => 'nullable|exists:stocks,id',
            'catatan' => 'nullable|string|max:5000',
        ];
    }

    public function save(): void
    {
        if ($this->repairId) {
            $this->authorize('perbaikan.edit');
        } else {
            $this->authorize('perbaikan.create');
        }

        $validated = $this->validate();

        $data = [
            'item_id' => $validated['item_id'],
            'tanggal_laporan' => $validated['tanggal_laporan'],
            'dilaporkan_oleh' => $validated['dilaporkan_oleh'] ?: auth()->id(),
            'deskripsi_kerusakan' => $validated['deskripsi_kerusakan'],
            'tingkat_kerusakan' => $validated['tingkat_kerusakan'],
            'tindakan' => $validated['tindakan'],
            'tindakan_detail' => $validated['tindakan_detail'],
            'ditangani_oleh' => $validated['ditangani_oleh'],
            'vendor_eksternal' => $validated['vendor_eksternal'],
            'biaya' => $validated['biaya'] ? (float) $validated['biaya'] : null,
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'status_akhir' => $validated['status_akhir'],
            'stock_id' => $validated['stock_id'],
            'catatan' => $validated['catatan'],
        ];

        $isCompleting = !empty($validated['tanggal_selesai']) && !empty($validated['status_akhir']);

        if ($this->repairId) {
            $wasAlreadyComplete = !empty($this->repair->tanggal_selesai) && !empty($this->repair->status_akhir);

            $this->repair->update($data);

            if ($isCompleting && !$wasAlreadyComplete) {
                $this->updateItemStatus($validated['item_id']);
            }

            session()->flash('success', 'Riwayat perbaikan berhasil diperbarui.');
        } else {
            $data['created_by'] = auth()->id();
            $repair = RepairHistory::create($data);

            if ($isCompleting) {
                $this->updateItemStatus($validated['item_id']);
            }

            session()->flash('success', 'Riwayat perbaikan berhasil ditambahkan.');
        }

        $this->redirectRoute('perbaikan.index', navigate: true);
    }

    private function updateItemStatus(int $itemId): void
    {
        $item = Item::find($itemId);
        if (!$item) return;

        $oldKondisi = $item->kondisi;
        $oldStatus = $item->status_penggunaan;

        $item->update([
            'kondisi' => 'Baik',
            'status_penggunaan' => 'Digunakan',
        ]);

        if ($oldKondisi !== 'Baik' || $oldStatus !== 'Digunakan') {
            ItemStatusHistory::create([
                'item_id' => $itemId,
                'kondisi_sebelumnya' => $oldKondisi,
                'kondisi_baru' => 'Baik',
                'status_sebelumnya' => $oldStatus,
                'status_baru' => 'Digunakan',
                'keterangan' => 'Status diperbarui otomatis setelah perbaikan selesai (ID: ' . ($this->repair?->id ?? '(baru)') . ')',
                'created_by' => auth()->id(),
            ]);
        }
    }

    public function render()
    {
        $users = User::where('is_active', true)->orderBy('name')->get();

        $stocks = Stock::orderBy('nama')->get();

        $items = [];
        if (strlen($this->item_search) >= 2) {
            $items = Item::query()
                ->where(function ($q) {
                    $q->where('kode_aset', 'like', '%' . $this->item_search . '%')
                        ->orWhere('nama', 'like', '%' . $this->item_search . '%');
                })
                ->orderBy('kode_aset')
                ->limit(10)
                ->get();
        }

        $tingkatOptions = ['Ringan', 'Sedang', 'Berat', 'Kritis'];

        $isEditing = $this->repairId !== null;

        return view('livewire.perbaikan.perbaikan-form', compact(
            'users', 'stocks', 'items', 'tingkatOptions', 'isEditing'
        ))->title($isEditing ? 'Edit Perbaikan' : 'Tambah Perbaikan');
    }
}

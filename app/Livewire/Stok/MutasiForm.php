<?php

namespace App\Livewire\Stok;

use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
class MutasiForm extends Component
{
    public $movementId = null;

    public $stock_id = '';
    public $tipe = 'masuk';
    public $jumlah = 1;
    public $harga_satuan = '';
    public $item_id = '';
    public $from_location_id = '';
    public $to_location_id = '';
    public $referensi = '';
    public $keterangan = '';

    public $selectedStock = null;

    public function mount($id = null, $tipe = null)
    {
        if ($tipe && in_array($tipe, ['masuk', 'keluar'])) {
            $this->tipe = $tipe;
        }

        if ($id) {
            $movement = StockMovement::findOrFail($id);

            $this->movementId = $movement->id;
            $this->stock_id = (string) $movement->stock_id;
            $this->tipe = $movement->tipe;
            $this->jumlah = $movement->jumlah;
            $this->harga_satuan = $movement->harga_satuan;
            $this->item_id = (string) $movement->item_id;
            $this->from_location_id = (string) $movement->from_location_id;
            $this->to_location_id = (string) $movement->to_location_id;
            $this->referensi = $movement->referensi;
            $this->keterangan = $movement->keterangan;

            $this->updatedStockId($this->stock_id);
        }
    }

    public function rules()
    {
        $rules = [
            'stock_id' => 'required|exists:stocks,id',
            'tipe' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'nullable|numeric|min:0',
            'item_id' => 'nullable|exists:items,id',
            'referensi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ];

        if ($this->tipe === 'masuk') {
            $rules['to_location_id'] = 'required|exists:locations,id';
            $rules['from_location_id'] = 'nullable|exists:locations,id';
        } elseif ($this->tipe === 'keluar') {
            $rules['from_location_id'] = 'required|exists:locations,id';
            $rules['to_location_id'] = 'nullable|exists:locations,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'stock_id.required' => 'Stok harus dipilih.',
            'tipe.required' => 'Tipe mutasi harus dipilih.',
            'jumlah.required' => 'Jumlah harus diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'from_location_id.required' => 'Lokasi asal harus dipilih.',
            'to_location_id.required' => 'Lokasi tujuan harus dipilih.',
        ];
    }

    public function updatedStockId($value)
    {
        $this->selectedStock = null;
        if ($value) {
            $this->selectedStock = Stock::with('lokasi', 'kategori')->find($value);
            if ($this->selectedStock) {
                $this->from_location_id = (string) $this->selectedStock->lokasi_id;
                $this->harga_satuan = $this->selectedStock->harga_satuan;
            }
        }
    }

    public function updatedTipe($value)
    {
        if ($value === 'masuk') {
            $this->from_location_id = '';
        } elseif ($value === 'keluar') {
            $this->to_location_id = '';
            if ($this->selectedStock) {
                $this->from_location_id = (string) $this->selectedStock->lokasi_id;
            }
        }
    }

    public function save()
    {
        if ($this->movementId) {
            $this->authorize('stok.edit');
        } elseif ($this->tipe === 'masuk') {
            $this->authorize('stok.masuk');
        } else {
            $this->authorize('stok.keluar');
        }

        $this->validate();

        $stock = Stock::findOrFail($this->stock_id);

        if ($this->tipe === 'keluar' && $this->jumlah > $stock->jumlah_stok) {
            session()->flash('error', 'Jumlah stok tidak mencukupi. Stok tersedia: ' . $stock->jumlah_stok . ' ' . $stock->satuan);
            return;
        }

        DB::transaction(function () use ($stock) {
            $movement = StockMovement::updateOrCreate(
                ['id' => $this->movementId],
                [
                    'stock_id' => $this->stock_id,
                    'tipe' => $this->tipe,
                    'jumlah' => $this->jumlah,
                    'harga_satuan' => $this->harga_satuan !== '' ? (float) $this->harga_satuan : null,
                    'item_id' => $this->item_id ?: null,
                    'from_location_id' => $this->from_location_id ?: null,
                    'to_location_id' => $this->to_location_id ?: null,
                    'referensi' => $this->referensi,
                    'keterangan' => $this->keterangan,
                    'created_by' => Auth::id(),
                ]
            );

            if (!$this->movementId) {
                if ($this->tipe === 'masuk') {
                    $stock->increment('jumlah_stok', $this->jumlah);
                } elseif ($this->tipe === 'keluar') {
                    $stock->decrement('jumlah_stok', $this->jumlah);
                }
            }
        });

        session()->flash('success', $this->movementId
            ? 'Mutasi stok berhasil diperbarui.'
            : 'Mutasi stok berhasil dicatat.');

        return $this->redirect(route('stok.index'), navigate: true);
    }

    public function render()
    {
        $stocks = Stock::with('kategori', 'lokasi')->orderBy('nama')->get();
        $items = Item::orderBy('nama')->get();
        $locations = Location::where('is_active', true)->orderBy('nama')->get();

        return view('livewire.stok.mutasi-form', compact('stocks', 'items', 'locations'))
            ->title($this->movementId ? 'Edit Mutasi Stok' : 'Mutasi Stok');
    }
}

<?php

namespace App\Livewire\Stok;

use App\Models\Stock;
use App\Models\Category;
use App\Models\Location;
use App\Models\ItemTemplate;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
class StokForm extends Component
{
    public $stockId = null;

    public $nama = '';
    public $kategori_id = '';
    public $item_template_id = '';
    public $lokasi_id = '';
    public $jumlah_stok = 0;
    public $ambang_batas_minimum = 0;
    public $satuan = '';
    public $harga_satuan = '';
    public $vendor = '';
    public $catatan = '';

    public function mount($id = null)
    {
        if ($id) {
            $stock = Stock::findOrFail($id);

            $this->stockId = $stock->id;
            $this->nama = $stock->nama;
            $this->kategori_id = (string) $stock->kategori_id;
            $this->item_template_id = (string) $stock->item_template_id;
            $this->lokasi_id = (string) $stock->lokasi_id;
            $this->jumlah_stok = $stock->jumlah_stok;
            $this->ambang_batas_minimum = $stock->ambang_batas_minimum;
            $this->satuan = $stock->satuan;
            $this->harga_satuan = $stock->harga_satuan;
            $this->vendor = $stock->vendor;
            $this->catatan = $stock->catatan;
        }
    }

    public function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|exists:categories,id',
            'item_template_id' => 'nullable|exists:item_templates,id',
            'lokasi_id' => 'required|exists:locations,id',
            'jumlah_stok' => 'required|integer|min:0',
            'ambang_batas_minimum' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'harga_satuan' => 'nullable|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'catatan' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama stok harus diisi.',
            'kategori_id.required' => 'Kategori harus dipilih.',
            'lokasi_id.required' => 'Lokasi harus dipilih.',
            'jumlah_stok.required' => 'Jumlah stok harus diisi.',
            'jumlah_stok.integer' => 'Jumlah stok harus berupa angka.',
            'jumlah_stok.min' => 'Jumlah stok tidak boleh negatif.',
            'ambang_batas_minimum.required' => 'Ambang batas minimum harus diisi.',
            'satuan.required' => 'Satuan harus diisi.',
        ];
    }

    public function save()
    {
        if ($this->stockId) {
            $this->authorize('stok.edit');
        } else {
            $this->authorize('stok.create');
        }

        $this->validate();

        $data = [
            'nama' => $this->nama,
            'kategori_id' => $this->kategori_id,
            'item_template_id' => $this->item_template_id ?: null,
            'lokasi_id' => $this->lokasi_id,
            'jumlah_stok' => $this->jumlah_stok,
            'ambang_batas_minimum' => $this->ambang_batas_minimum,
            'satuan' => $this->satuan,
            'harga_satuan' => $this->harga_satuan ?: null,
            'vendor' => $this->vendor,
            'catatan' => $this->catatan,
        ];

        $isUpdate = $this->stockId !== null;
        $oldData = $isUpdate ? Stock::find($this->stockId)?->toArray() : null;

        $stock = Stock::updateOrCreate(
            ['id' => $this->stockId],
            $data
        );

        if ($isUpdate) {
            activity('stok')
                ->causedBy(auth()->user())
                ->performedOn($stock)
                ->event('updated')
                ->withProperties([
                    'old' => $oldData,
                    'new' => $data,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Mengubah stok {$stock->nama}.");

            session()->flash('success', 'Stok berhasil diperbarui.');
        } else {
            activity('stok')
                ->causedBy(auth()->user())
                ->performedOn($stock)
                ->event('created')
                ->withProperties([
                    'data' => $data,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Menambahkan stok baru {$stock->nama}.");

            session()->flash('success', 'Stok berhasil ditambahkan.');
        }

        return $this->redirect(route('stok.index'), navigate: true);
    }

    public function updatedKategoriId($value)
    {
        $this->item_template_id = '';
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->orderBy('nama')->get();

        $itemTemplates = collect();
        if ($this->kategori_id) {
            $itemTemplates = ItemTemplate::where('kategori_id', $this->kategori_id)
                ->where('is_active', true)
                ->orderBy('nama')
                ->get();
        }

        $locations = Location::where('is_active', true)->orderBy('nama')->get();

        return view('livewire.stok.stok-form', compact('categories', 'itemTemplates', 'locations'))
            ->title($this->stockId ? 'Edit Stok' : 'Tambah Stok');
    }
}

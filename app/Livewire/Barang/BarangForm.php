<?php

namespace App\Livewire\Barang;

use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use App\Models\ItemTemplate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

#[Layout('layouts.app')]
class BarangForm extends Component
{
    use WithFileUploads;

    public ?Item $item = null;

    public string $kode_aset = '';
    public string $nama = '';
    public string $deskripsi = '';
    public ?string $kategori_id = null;
    public ?string $lokasi_id = null;
    public ?string $item_template_id = null;
    public ?string $parent_id = null;
    public string $nomor_seri = '';
    public string $tanggal_pengadaan = '';
    public string $vendor = '';
    public string $sumber = '';
    public ?string $harga = null;
    public $foto = null;
    public ?string $foto_existing = null;
    public string $kondisi = 'Baik';
    public string $status_penggunaan = 'Idle';
    public ?string $jumlah = null;
    public string $satuan = 'unit';
    public string $catatan = '';

    public array $kondisiOptions = [
        'Baik', 'Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan', 'Sudah Diperbaiki', 'Afkir-Dihapuskan'
    ];

    public array $statusOptions = [
        'Digunakan', 'Idle', 'Dipinjam', 'Dalam Perbaikan', 'Menunggu Pembuangan'
    ];

    public array $sumberOptions = [
        'Pembelian', 'Hibah', 'Bantuan', 'Swadaya', 'Transfer', 'Lainnya'
    ];

    public function mount(?Item $item = null): void
    {
        $this->item = $item;

        if ($item && $item->exists) {
            $this->kode_aset = $item->kode_aset;
            $this->nama = $item->nama;
            $this->deskripsi = $item->deskripsi ?? '';
            $this->kategori_id = $item->kategori_id ? (string) $item->kategori_id : null;
            $this->lokasi_id = $item->lokasi_id ? (string) $item->lokasi_id : null;
            $this->item_template_id = $item->item_template_id ? (string) $item->item_template_id : null;
            $this->parent_id = $item->parent_id ? (string) $item->parent_id : null;
            $this->nomor_seri = $item->nomor_seri ?? '';
            $this->tanggal_pengadaan = $item->tanggal_pengadaan?->format('Y-m-d') ?? '';
            $this->vendor = $item->vendor ?? '';
            $this->sumber = $item->sumber ?? '';
            $this->harga = $item->harga ? (string) $item->harga : null;
            $this->foto_existing = $item->foto;
            $this->kondisi = $item->kondisi ?? 'Baik';
            $this->status_penggunaan = $item->status_penggunaan ?? 'Idle';
            $this->jumlah = $item->jumlah ? (string) $item->jumlah : null;
            $this->satuan = $item->satuan ?? 'unit';
            $this->catatan = $item->catatan ?? '';
        }
    }

    public function updatedKategoriId(): void
    {
        $this->generateKodeAset();
    }

    public function updatedLokasiId(): void
    {
        $this->generateKodeAset();
    }

    protected function generateKodeAset(): void
    {
        if ($this->item && $this->item->exists) {
            return;
        }

        if (empty($this->lokasi_id) || empty($this->kategori_id)) {
            return;
        }

        $lokasi = Location::find($this->lokasi_id);
        $kategori = Category::find($this->kategori_id);

        if (!$lokasi || !$kategori) {
            return;
        }

        $lokasiKode = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $lokasi->kode_lokasi ?? $lokasi->nama), 0, 5));
        $kategoriKode = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $kategori->slug ?? $kategori->nama), 0, 5));

        $lastItem = Item::where('kode_aset', 'like', "{$lokasiKode}-{$kategoriKode}-%")
            ->orderBy('kode_aset', 'desc')
            ->first();

        if ($lastItem && preg_match('/-(\d+)$/', $lastItem->kode_aset, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = 1;
        }

        $this->kode_aset = sprintf('%s-%s-%03d', $lokasiKode, $kategoriKode, $nextNumber);
    }

    protected function generateQrCode(string $kodeAset): string
    {
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($kodeAset);

        $filename = 'qr_codes/' . $kodeAset . '.png';
        Storage::disk('public')->put($filename, $qrCode);

        return $filename;
    }

    public function save(): void
    {
        if ($this->item && $this->item->exists) {
            $this->authorize('barang.edit');
        } else {
            $this->authorize('barang.create');
        }

        $rules = [
            'kode_aset' => 'required|string|max:50|unique:items,kode_aset,' . ($this->item?->id ?? 'NULL'),
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:5000',
            'kategori_id' => 'nullable|exists:categories,id',
            'lokasi_id' => 'nullable|exists:locations,id',
            'item_template_id' => 'nullable|exists:item_templates,id',
            'parent_id' => 'nullable|exists:items,id',
            'nomor_seri' => 'nullable|string|max:100',
            'tanggal_pengadaan' => 'nullable|date',
            'vendor' => 'nullable|string|max:255',
            'sumber' => 'nullable|string|max:100',
            'harga' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|max:2048',
            'kondisi' => 'required|string|max:50',
            'status_penggunaan' => 'required|string|max:50',
            'jumlah' => 'nullable|integer|min:1',
            'satuan' => 'required|string|max:50',
            'catatan' => 'nullable|string|max:5000',
        ];

        if ($this->item && $this->item->exists) {
            $rules['parent_id'] = 'nullable|exists:items,id|not_in:' . $this->item->id;
        }

        $validated = $this->validate($rules);

        $data = [
            'kode_aset' => $validated['kode_aset'],
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? '',
            'kategori_id' => $validated['kategori_id'] ? (int) $validated['kategori_id'] : null,
            'lokasi_id' => $validated['lokasi_id'] ? (int) $validated['lokasi_id'] : null,
            'item_template_id' => $validated['item_template_id'] ? (int) $validated['item_template_id'] : null,
            'parent_id' => $validated['parent_id'] ? (int) $validated['parent_id'] : null,
            'nomor_seri' => $validated['nomor_seri'] ?? '',
            'tanggal_pengadaan' => $validated['tanggal_pengadaan'] ?? null,
            'vendor' => $validated['vendor'] ?? '',
            'sumber' => $validated['sumber'] ?? '',
            'harga' => $validated['harga'] ? (float) $validated['harga'] : null,
            'kondisi' => $validated['kondisi'],
            'status_penggunaan' => $validated['status_penggunaan'],
            'jumlah' => $validated['jumlah'] ? (int) $validated['jumlah'] : null,
            'satuan' => $validated['satuan'],
            'catatan' => $validated['catatan'] ?? '',
        ];

        if ($this->foto) {
            $data['foto'] = $this->foto->store('foto_barang', 'public');
        } elseif ($this->item && $this->item->exists) {
            $data['foto'] = $this->item->foto;
        }

        if ($this->item && $this->item->exists) {
            $oldData = $this->item->toArray();
            $data['updated_by'] = auth()->id();
            $this->item->update($data);

            activity('barang')
                ->causedBy(auth()->user())
                ->performedOn($this->item)
                ->event('updated')
                ->withProperties([
                    'old' => $oldData,
                    'new' => $data,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Mengubah barang {$this->item->nama} ({$this->item->kode_aset}).");

            session()->flash('success', 'Barang berhasil diperbarui.');
        } else {
            $data['qr_code'] = $this->generateQrCode($validated['kode_aset']);
            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();
            $item = Item::create($data);

            activity('barang')
                ->causedBy(auth()->user())
                ->performedOn($item)
                ->event('created')
                ->withProperties([
                    'data' => $data,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Menambahkan barang baru {$item->nama} ({$item->kode_aset}).");

            session()->flash('success', 'Barang berhasil ditambahkan.');
        }

        $this->redirectRoute('barang.index', navigate: true);
    }

    #[Title('Edit Barang')]
    public function render()
    {
        $kategoris = Category::where('is_active', true)->orderBy('nama')->get();
        $lokasis = Location::where('is_active', true)->orderBy('nama')->get();
        $templates = ItemTemplate::where('is_active', true)->orderBy('nama')->get();

        $parents = Item::query()
            ->where('id', '!=', $this->item?->id)
            ->when($this->item && $this->item->exists, function ($q) {
                $q->whereNotIn('id', $this->item->children->pluck('id'));
            })
            ->orderBy('kode_aset')
            ->get();

        return view('livewire.barang.barang-form', compact('kategoris', 'lokasis', 'templates', 'parents'));
    }
}

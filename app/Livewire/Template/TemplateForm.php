<?php

namespace App\Livewire\Template;

use App\Models\Category;
use App\Models\ItemTemplate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Form Template Barang')]
class TemplateForm extends Component
{
    use WithFileUploads;

    public ?ItemTemplate $template = null;

    public string $nama = '';

    public string $merk = '';

    public string $tipe_model = '';

    public string $satuan = 'unit';

    public string $spesifikasi = '';

    public string $kategori_id = '';

    public ?string $estimasi_harga = null;

    public $gambar = null;

    public bool $has_serial_number = false;

    public bool $is_active = true;

    public ?string $existingGambar = null;

    protected function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'tipe_model' => 'nullable|string|max:255',
            'satuan' => 'required|in:unit,pcs,box,meter,buah,set,pasang',
            'spesifikasi' => 'nullable|string',
            'kategori_id' => 'required|exists:categories,id',
            'estimasi_harga' => 'nullable|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'has_serial_number' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'nama.required' => 'Nama template harus diisi.',
        'nama.max' => 'Nama template maksimal 255 karakter.',
        'satuan.required' => 'Satuan harus dipilih.',
        'satuan.in' => 'Satuan yang dipilih tidak valid.',
        'kategori_id.required' => 'Kategori harus dipilih.',
        'kategori_id.exists' => 'Kategori tidak ditemukan.',
        'estimasi_harga.numeric' => 'Estimasi harga harus berupa angka.',
        'estimasi_harga.min' => 'Estimasi harga tidak boleh negatif.',
        'gambar.image' => 'File harus berupa gambar.',
        'gambar.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
        'gambar.max' => 'Ukuran gambar maksimal 2MB.',
    ];

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->template = ItemTemplate::findOrFail($id);
            $this->nama = $this->template->nama;
            $this->merk = $this->template->merk ?? '';
            $this->tipe_model = $this->template->tipe_model ?? '';
            $this->satuan = $this->template->satuan;
            $this->spesifikasi = $this->template->spesifikasi ?? '';
            $this->kategori_id = (string) $this->template->kategori_id;
            $this->estimasi_harga = $this->template->estimasi_harga ? (string) $this->template->estimasi_harga : null;
            $this->existingGambar = $this->template->gambar;
            $this->has_serial_number = $this->template->has_serial_number;
            $this->is_active = $this->template->is_active;
        }
    }

    public function save(): void
    {
        if ($this->template) {
            if (!auth()->user()->can('template.edit')) {
                $this->dispatch('alert', type: 'error', message: 'Anda tidak memiliki izin untuk mengedit template.');
                return;
            }
        } else {
            if (!auth()->user()->can('template.create')) {
                $this->dispatch('alert', type: 'error', message: 'Anda tidak memiliki izin untuk membuat template.');
                return;
            }
        }

        $this->validate();

        $data = [
            'nama' => $this->nama,
            'merk' => $this->merk ?: null,
            'tipe_model' => $this->tipe_model ?: null,
            'satuan' => $this->satuan,
            'spesifikasi' => $this->spesifikasi ?: null,
            'kategori_id' => $this->kategori_id,
            'estimasi_harga' => $this->estimasi_harga ?: null,
            'has_serial_number' => $this->has_serial_number,
            'is_active' => $this->is_active,
        ];

        if ($this->gambar) {
            $data['gambar'] = $this->gambar->store('template', 'public');
        }

        if ($this->template) {
            $oldData = $this->template->toArray();
            $this->template->update($data);

            activity('template')
                ->causedBy(auth()->user())
                ->performedOn($this->template)
                ->event('updated')
                ->withProperties([
                    'old' => $oldData,
                    'new' => $data,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Mengubah template barang {$this->template->nama}.");

            $message = 'Template berhasil diperbarui.';
        } else {
            $template = ItemTemplate::create($data);

            activity('template')
                ->causedBy(auth()->user())
                ->performedOn($template)
                ->event('created')
                ->withProperties([
                    'data' => $data,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log("Menambahkan template barang baru {$template->nama}.");

            $message = 'Template berhasil ditambahkan.';
        }

        $this->dispatch('template-saved');
        $this->dispatch('alert', type: 'success', message: $message);
        $this->redirect(route('template.index'), navigate: true);
    }

    public function removeGambar(): void
    {
        $this->gambar = null;
        $this->existingGambar = null;
    }

    public function render()
    {
        $kategoris = Category::where('is_active', true)->orderBy('nama')->get();

        $satuanOptions = [
            'unit' => 'Unit',
            'pcs' => 'Pcs',
            'box' => 'Box',
            'meter' => 'Meter',
            'buah' => 'Buah',
            'set' => 'Set',
            'pasang' => 'Pasang',
        ];

        return view('livewire.template.template-form', compact('kategoris', 'satuanOptions'));
    }
}

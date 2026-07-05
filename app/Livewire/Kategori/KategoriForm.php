<?php

namespace App\Livewire\Kategori;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class KategoriForm extends Component
{
    public ?Category $kategori = null;

    public string $nama = '';

    public string $slug = '';

    public string $deskripsi = '';

    public ?string $parent_id = null;

    public string $icon = '';

    public bool $is_active = true;

    #[Layout('layouts.app')]
    public function mount(?Category $kategori = null): void
    {
        $this->kategori = $kategori;

        if ($kategori && $kategori->exists) {
            $this->nama = $kategori->nama;
            $this->slug = $kategori->slug;
            $this->deskripsi = $kategori->deskripsi ?? '';
            $this->parent_id = $kategori->parent_id ? (string) $kategori->parent_id : null;
            $this->icon = $kategori->icon ?? '';
            $this->is_active = $kategori->is_active;
        }
    }

    public function generateSlug(): void
    {
        $this->slug = Str::slug($this->nama);
    }

    public function updatedNama(): void
    {
        if (empty($this->slug) || $this->slug === Str::slug($this->kategori?->nama ?? '')) {
            $this->slug = Str::slug($this->nama);
        }
    }

    public function save(): void
    {
        if ($this->kategori && $this->kategori->exists) {
            $this->authorize('kategori.edit');
        } else {
            $this->authorize('kategori.create');
        }

        $slugUnique = 'unique:categories,slug';
        if ($this->kategori && $this->kategori->exists) {
            $slugUnique .= ',' . $this->kategori->id;
        }

        $validated = $this->validate([
            'nama' => 'required|string|max:255',
            'slug' => 'required|string|max:255|' . $slugUnique,
            'deskripsi' => 'nullable|string|max:1000',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    if ($value && $this->kategori && $this->kategori->exists && $value == $this->kategori->id) {
                        $fail('Kategori tidak bisa menjadi parent dari dirinya sendiri.');
                    }
                },
            ],
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $data = [
            'nama' => $validated['nama'],
            'slug' => $validated['slug'],
            'deskripsi' => $validated['deskripsi'] ?? '',
            'parent_id' => $validated['parent_id'] ? (int) $validated['parent_id'] : null,
            'icon' => $validated['icon'] ?? '',
            'is_active' => $validated['is_active'] ?? true,
        ];

        if ($this->kategori && $this->kategori->exists) {
            $this->kategori->update($data);
            session()->flash('success', 'Kategori berhasil diperbarui.');
        } else {
            Category::create($data);
            session()->flash('success', 'Kategori berhasil ditambahkan.');
        }

        $this->redirectRoute('kategori.list', navigate: true);
    }

    #[Title('Edit Kategori')]
    public function render()
    {
        $parents = Category::query()
            ->where('is_active', true)
            ->when($this->kategori && $this->kategori->exists, function ($query) {
                $query->where('id', '!=', $this->kategori->id)
                    ->whereNotIn('id', $this->kategori->children->pluck('id'));
            })
            ->orderBy('nama')
            ->get();

        return view('livewire.kategori.kategori-form', compact('parents'));
    }
}

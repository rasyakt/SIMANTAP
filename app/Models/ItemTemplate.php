<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama', 'merk', 'tipe_model', 'satuan', 'spesifikasi',
        'kategori_id', 'estimasi_harga', 'gambar', 'has_serial_number', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'estimasi_harga' => 'decimal:2',
            'has_serial_number' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'item_template_id');
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class, 'item_template_id');
    }
}

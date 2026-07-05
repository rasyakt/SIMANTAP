<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama', 'kategori_id', 'item_template_id', 'lokasi_id',
        'jumlah_stok', 'ambang_batas_minimum', 'satuan', 'harga_satuan',
        'vendor', 'catatan'
    ];

    protected function casts(): array
    {
        return [
            'jumlah_stok' => 'integer',
            'ambang_batas_minimum' => 'integer',
            'harga_satuan' => 'decimal:2',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function itemTemplate(): BelongsTo
    {
        return $this->belongsTo(ItemTemplate::class, 'item_template_id');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'lokasi_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'stock_id');
    }

    public function isLowStock(): bool
    {
        return $this->jumlah_stok <= $this->ambang_batas_minimum;
    }
}

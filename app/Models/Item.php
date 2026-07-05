<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kode_aset', 'nama', 'deskripsi', 'kategori_id', 'lokasi_id',
        'item_template_id', 'parent_id', 'nomor_seri', 'tanggal_pengadaan',
        'vendor', 'sumber', 'harga', 'foto', 'kondisi', 'status_penggunaan',
        'jumlah', 'satuan', 'catatan', 'qr_code', 'created_by', 'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengadaan' => 'date',
            'harga' => 'decimal:2',
            'jumlah' => 'integer',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'lokasi_id');
    }

    public function itemTemplate(): BelongsTo
    {
        return $this->belongsTo(ItemTemplate::class, 'item_template_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Item::class, 'parent_id');
    }

    public function components(): HasMany
    {
        return $this->hasMany(ItemComponent::class, 'parent_item_id');
    }

    public function componentOf(): HasMany
    {
        return $this->hasMany(ItemComponent::class, 'component_item_id');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ItemStatusHistory::class, 'item_id');
    }

    public function repairHistories(): HasMany
    {
        return $this->hasMany(RepairHistory::class, 'item_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

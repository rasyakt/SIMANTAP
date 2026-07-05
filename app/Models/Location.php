<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kode_lokasi', 'nama', 'tipe_lokasi', 'parent_id',
        'penanggung_jawab_id', 'kapasitas', 'deskripsi', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function penanggungJawab(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'lokasi_id');
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class, 'lokasi_id');
    }
}

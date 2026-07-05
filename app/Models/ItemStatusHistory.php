<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemStatusHistory extends Model
{
    protected $fillable = [
        'item_id', 'kondisi_sebelumnya', 'kondisi_baru',
        'status_sebelumnya', 'status_baru',
        'lokasi_sebelumnya_id', 'lokasi_baru_id',
        'keterangan', 'created_by'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

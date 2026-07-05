<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepairHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'item_id', 'tanggal_laporan', 'dilaporkan_oleh',
        'deskripsi_kerusakan', 'tingkat_kerusakan', 'tindakan',
        'tindakan_detail', 'ditangani_oleh', 'vendor_eksternal',
        'biaya', 'tanggal_selesai', 'status_akhir',
        'stock_id', 'catatan', 'created_by'
    ];

    protected function casts(): array
    {
        return [
            'tanggal_laporan' => 'date',
            'tanggal_selesai' => 'date',
            'biaya' => 'decimal:2',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dilaporkan_oleh');
    }

    public function penangan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

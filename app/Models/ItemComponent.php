<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemComponent extends Model
{
    protected $table = 'item_components';

    protected $fillable = [
        'parent_item_id', 'component_item_id', 'kuantitas', 'catatan'
    ];

    protected function casts(): array
    {
        return [
            'kuantitas' => 'integer',
        ];
    }

    public function parentItem(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'parent_item_id');
    }

    public function componentItem(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'component_item_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErpItemPrice extends Model
{
    protected $guarded = [];

    protected $casts = [
        'price_list_rate' => 'decimal:6',
        'valid_from' => 'date',
        'valid_upto' => 'date',
        'erp_modified_at' => 'datetime',
        'last_synced_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(ErpItem::class, 'item_code', 'item_code');
    }
}

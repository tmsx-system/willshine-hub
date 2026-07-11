<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErpItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_stock_item' => 'boolean',
        'disabled' => 'boolean',
        'has_batch_no' => 'boolean',
        'has_serial_no' => 'boolean',
        'erp_modified_at' => 'datetime',
        'last_synced_at' => 'datetime',
    ];

    public function catalog()
    {
        return $this->hasOne(ProductCatalog::class, 'item_id');
    }

    public function prices()
    {
        return $this->hasMany(ErpItemPrice::class, 'item_code', 'item_code');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProductCatalog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'daily_quantity' => 'decimal:2',
        'minimum_qty' => 'decimal:2',
        'maximum_qty' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(ErpCustomer::class, 'customer_id');
    }

    public function productCatalog()
    {
        return $this->belongsTo(ProductCatalog::class, 'product_catalog_id');
    }
}

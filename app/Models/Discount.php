<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $guarded = [];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'maximum_discount_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'per_customer_limit' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_stackable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function productCatalog()
    {
        return $this->belongsTo(ProductCatalog::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function customerType()
    {
        return $this->belongsTo(CustomerType::class);
    }

    public function customerAccount()
    {
        return $this->belongsTo(CustomerAccount::class);
    }
}

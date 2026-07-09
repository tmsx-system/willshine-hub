<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    protected $guarded = [];

    protected $casts = [
        'minimum_order_amount' => 'decimal:2',
        'allow_reward' => 'boolean',
        'allow_promo' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function customers()
    {
        return $this->hasMany(ErpCustomer::class);
    }
}

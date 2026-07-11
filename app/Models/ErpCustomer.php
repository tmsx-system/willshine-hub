<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErpCustomer extends Model
{
    protected $guarded = [];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'disabled' => 'boolean',
        'erp_modified_at' => 'datetime',
        'last_synced_at' => 'datetime',
    ];

    public function customerType()
    {
        return $this->belongsTo(CustomerType::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'customer_id');
    }

    public function customerAccounts()
    {
        return $this->hasMany(CustomerAccount::class, 'customer_id');
    }

    public function productCatalogRules()
    {
        return $this->hasMany(CustomerProductCatalog::class, 'customer_id');
    }
}

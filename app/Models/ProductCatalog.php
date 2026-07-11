<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCatalog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_visible' => 'boolean',
        'is_featured' => 'boolean',
        'allow_decimal_qty' => 'boolean',
        'show_stock' => 'boolean',
        'show_price' => 'boolean',
    ];

    public function item()
    {
        return $this->belongsTo(ErpItem::class, 'item_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function customerRules()
    {
        return $this->hasMany(CustomerProductCatalog::class, 'product_catalog_id');
    }
}

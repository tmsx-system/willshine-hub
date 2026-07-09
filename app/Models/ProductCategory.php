<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function catalogs()
    {
        return $this->hasMany(ProductCatalog::class, 'category_id');
    }
}

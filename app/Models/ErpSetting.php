<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErpSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'enable_auto_sync' => 'boolean',
        'last_sync_customer' => 'datetime',
        'last_sync_item' => 'datetime',
        'last_sync_stock' => 'datetime',
        'last_sync_price' => 'datetime',
    ];
}

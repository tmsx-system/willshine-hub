<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardTransaction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'points' => 'integer',
        'balance_after' => 'integer',
    ];

    public function customerAccount()
    {
        return $this->belongsTo(CustomerAccount::class);
    }

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
}

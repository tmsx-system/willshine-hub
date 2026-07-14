<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardRedemption extends Model
{
    protected $guarded = [];

    protected $casts = [
        'points_spent' => 'integer',
        'processed_at' => 'datetime',
    ];

    public function customerAccount()
    {
        return $this->belongsTo(CustomerAccount::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}

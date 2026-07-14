<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $guarded = [];

    protected $casts = [
        'points_required' => 'integer',
        'valid_until' => 'date',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    public function redemptions()
    {
        return $this->hasMany(RewardRedemption::class);
    }

    public function transactions()
    {
        return $this->hasMany(RewardTransaction::class);
    }
}

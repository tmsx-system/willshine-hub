<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CustomerAccount extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'customer_name',
        'customer_type_id',
        'is_active',
        'can_order',
        'can_view_price',
        'can_view_reward',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'can_order' => 'boolean',
            'can_view_price' => 'boolean',
            'can_view_reward' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(ErpCustomer::class, 'customer_id');
    }

    public function customerType()
    {
        return $this->belongsTo(CustomerType::class, 'customer_type_id');
    }
}

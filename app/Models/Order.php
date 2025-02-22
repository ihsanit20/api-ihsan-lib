<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'user_id',
        'total_price',
        'discount_percentage',
        'discount_amount',
        'adjust_amount',
        'payable_amount',
        'paid_amount',
        'due_amount',
        'status',
        'delivery_charge',
        'shipping_details',
    ];

    protected $casts = [
        'shipping_details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

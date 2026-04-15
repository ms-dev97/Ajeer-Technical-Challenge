<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'cartable_type',
    'cartable_id',
    'unit_price',
    'cart_id',
    'time_slot_id',
    'booking_date'
])]
class CartItem extends Model
{
    public function cartable()
    {
        return $this->morphTo();
    }
}

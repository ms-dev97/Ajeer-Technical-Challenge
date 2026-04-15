<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'customer_name',
    'total_price',
    'status'
])]
class Cart extends Model
{
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}

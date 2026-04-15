<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'price', 'duration_in_minutes', 'capacity_per_slot', 'is_active'])]
class Service extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function cartItems()
    {
        return $this->morphMany(CartItem::class, 'cartable');
    }
}

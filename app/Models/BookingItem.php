<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'booking_id',
    'service_id',
    'booking_date',
    'time_slot_id',
    'status',
])]
class BookingItem extends Model
{
    //
}

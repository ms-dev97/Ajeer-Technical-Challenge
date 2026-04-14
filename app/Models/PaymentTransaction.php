<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'gateway',
    'amount',
    'currency',
    'status',
    'gateway_transaction_id',
    'city',
    'module'
])]
class PaymentTransaction extends Model
{
    //
}

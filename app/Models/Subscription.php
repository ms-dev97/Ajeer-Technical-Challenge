<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['status', 'trial_ends_at', 'starts_at', 'ends_at'])]
class Subscription extends Model
{
    //
}

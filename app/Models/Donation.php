<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'amount',
        'payment_method',
        'transaction_id',
        'reason',
    ];

    //
}

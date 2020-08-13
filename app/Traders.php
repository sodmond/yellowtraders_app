<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Traders extends Model
{
    protected $fillable = [
        'trader_id', 'trader_type',
        'full_name', 'marital_status', 'gender',
        'address', 'phone', 'other_phone',
        'dob', 'nationality', 'state', 'lga',
        'email', 'image', 'contact_name', 'contact_phone', 'referral',
    ];
}

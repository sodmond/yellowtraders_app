<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payouts extends Model
{
    protected $fillable = [
        'investment_id', 'roi',
    ];
}

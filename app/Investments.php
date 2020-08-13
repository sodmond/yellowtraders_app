<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Investments extends Model
{
    protected $fillable = [
        'trader_id',
        'amount', 'amount_in_words', 'monthly_roi', 'monthly_%',
        'duration', 'purpose', 'start_date', 'end_date',
        'status',
    ];
}

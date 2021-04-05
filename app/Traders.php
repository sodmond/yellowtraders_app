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

    public function repTraderType($trader)
    {
        $convertTrader = [];
        switch ($trader->trader_type) {
            case 1:
                $convertTrader = array_replace(json_decode(json_encode($trader), true), ['trader_type' => 'Yellow Trader']);
                break;
            case 2:
                $convertTrader = array_replace(json_decode(json_encode($trader), true), ['trader_type' => 'Junior Trader']);
                break;
            default:
                $convertTrader = array_replace(json_decode(json_encode($trader), true), ['trader_type' => 'Corporate Trader']);
                break;
        }
        return $convertTrader;
    }
}

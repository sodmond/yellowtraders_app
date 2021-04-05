<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TraderResource;
use App\Investments;
use App\Traders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TraderController extends Controller
{
    public function __construct() {
        #$this->trader_id = auth()->user()->username;
    }
    public function dashboard()
    {
        $trader_id = auth()->user()->username;
        $trader = Traders::join('investments', 'traders.trader_id', '=', 'investments.trader_id')
                    ->select('traders.trader_id', 'traders.full_name', 'investments.amount',
                        'investments.monthly_roi', 'investments.start_date', 'investments.end_date',
                        'investments.status')
                    ->where('traders.trader_id', $trader_id)
                    ->first();
        $convertTrader = new Investments();
        return new TraderResource($convertTrader->repInvStatus($trader));
    }

    public function traderProfile()
    {
        $trader_id = auth()->user()->username;
        $traderProfile = Traders::join('bank_accounts', 'traders.trader_id', '=', 'bank_accounts.trader_id')
                    ->select('traders.trader_id', 'traders.full_name', 'traders.trader_type', 'traders.email',
                        'traders.image', 'bank_accounts.bank_name', 'bank_accounts.holder_name',
                        DB::raw('LPAD(bank_accounts.account_number, 10, 0) AS account_number'))
                    ->where('traders.trader_id', $trader_id)
                    ->first();
        $convertTrader = new Traders;
        return new TraderResource($convertTrader->repTraderType($traderProfile));
    }
}

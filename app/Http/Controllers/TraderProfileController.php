<?php

namespace App\Http\Controllers;

use App\Investments;
use App\Traders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TraderProfileController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
        //return view('admin.trader_profile');
        redirect('/admin/dashboard');
    }

    public function show($id)
    {
        $trader = Traders::find($id);
        $bank = DB::table('bank_accounts')->where('trader_id', $trader->trader_id)->first();
        $inv = Investments::where('trader_id', $trader->trader_id)->first();
        $invLog = DB::table('investment_logs')->where('investment_id', $inv->id)->latest()->get();
        return view('admin.trader_profile', [
            'trader' => $trader,
            'bank' => $bank,
            'inv' => $inv,
            'invLog' => $invLog
        ]);
    }

    public function getMou($id)
    {
        $getTraderInfo = Investments::join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                    ->where('investments.id', $id)
                    ->first();
        return view('admin.preview_mou', ['getTraderInfo' => $getTraderInfo]);
    }
}

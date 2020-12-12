<?php

namespace App\Http\Controllers;

use App\Exports\AllTradersExport;
use Illuminate\Http\Request;
use App\Traders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class TradersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function allTraders()
    {
        return view('admin.all_traders');
    }

    private function getTraders($traderType)
    {
        $get_trader = Traders::select('id', 'trader_id', 'full_name', 'email')
                    ->where('trader_type', $traderType)
                    ->orderBy('full_name')
                    ->paginate(25);
        return $get_trader;
    }

    public function yellow()
    {
        $tradersList = $this->getTraders(1);
        return view('admin.yellow_traders', ['tradersList' => $tradersList]);
    }

    public function junior()
    {
        $tradersList = $this->getTraders(2);
        return view('admin.junior_traders', ['tradersList' => $tradersList]);
    }

    public function corporate()
    {
        $tradersList = $this->getTraders(3);
        return view('admin.corporate_traders', ['tradersList' => $tradersList]);
    }

    public function searchTraders()
    {
        return view('admin.search_trader');
    }

    public function search(Request $request)
    {
        if ($request->search == "") {
            return Redirect::back();
        }
        $get_trader = Traders::select('id', 'trader_id', 'full_name', 'email')
                    ->where('trader_id', $request->search)
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', $request->search)
                    ->orderBy('full_name')
                    ->limit(25)
                    ->get();
        return view('admin.search_trader', ['tradersList' => $get_trader]);
    }

    public function exportTraders($id)
    {
        $getTraderType = DB::table('trader_types')->where('id', $id)->select('name')->first();
        $traderType = $getTraderType->name;
        $filename = $traderType.'-traders-'.date('Y-m-d').'.xlsx';
        return Excel::download(new AllTradersExport($id), $filename);
    }
}

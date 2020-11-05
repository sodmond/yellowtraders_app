<?php

namespace App\Http\Controllers;

use App\Investments;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPayoutMail;
use App\Traders;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Exports\TradersToPayExport;
use App\Payouts;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tradersToPay = Investments::join('payouts', 'payouts.investment_id', '=', 'investments.id')
                    ->join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                    ->join('bank_accounts', 'traders.trader_id', '=', 'bank_accounts.trader_id')
                    ->where('payouts.status', 0)
                    ->select('payouts.*', 'investments.amount', 'investments.monthly_pcent',
                            'investments.duration', 'investments.start_date', 'investments.end_date', 'traders.trader_id',
                            'traders.full_name', 'bank_accounts.bank_name', 'bank_accounts.account_number')
                    ->orderBy('payouts.id')
                    ->take(10)
                    ->get();
        $all_investors = Traders::count();
        $payments_total = DB::table('received_payments')->count();
        $payments_today = DB::table('received_payments')->where('status', 1)->count();
        $investment_total = Investments::count();
        $investment_active = Investments::where('status', 2)->count();
        $payouts_total = DB::table('payouts')->count();
        $payouts_unconfirmed = DB::table('payouts')->where('status', 0)->count();
        $payoutChart = Payouts::select('roi', DB::raw('DAYOFWEEK(updated_at) as weekday'))->whereRaw('WEEk(updated_at) = WEEK(CURDATE()) AND status=1')->get();
        $payinChart = DB::table('investment_logs')->select('amount', DB::raw('DAYOFWEEK(created_at) as weekday'))->whereRaw('WEEk(created_at) = WEEK(CURDATE()) AND status=2')->get();
        return view('admin.dashboard', [
            'tradersToPay'          =>  $tradersToPay,
            'all_investors'         =>  $all_investors,
            'payments_total'        =>  $payments_total,
            'payments_today'        =>  $payments_today,
            'investment_total'      =>  $investment_total,
            'investment_active'     =>  $investment_active,
            'payouts_total'         =>  $payouts_total,
            'payouts_unconfirmed'   =>  $payouts_unconfirmed,
            'payoutChart'           =>  $payoutChart,
            'payinChart'            =>  $payinChart
        ]);
    }

    public function reportAnalysis($newDate="", $filterMonth="", $filterYear="")
    {
        if ($newDate != ""){
            $payoutChart = Payouts::select('roi', DB::raw('DAYOFWEEK(updated_at) as weekday'))->whereRaw('WEEk(updated_at) = WEEK("'.$newDate.'") AND status=1')->get();
            $payinChart = DB::table('investment_logs')->select('amount', DB::raw('DAYOFWEEK(created_at) as weekday'))->whereRaw('WEEk(created_at) = WEEK("'.$newDate.'") AND status=2')->get();
            return response()->json([
                'payoutChart' => $payoutChart,
                'payinChart' => $payinChart
            ]);
        }
        $all_investors = Traders::count();
        $payments_total = DB::table('received_payments')->count();
        $payments_today = DB::table('received_payments')->where('status', 1)->count();
        $investment_total = Investments::count();
        $investment_active = Investments::where('status', 2)->count();
        $payouts_total = DB::table('payouts')->count();
        $payouts_unconfirmed = DB::table('payouts')->where('status', 0)->count();
        $payoutChart = Payouts::select('roi', DB::raw('DAYOFWEEK(updated_at) as weekday'))->whereRaw('WEEk(updated_at) = WEEK(CURDATE()) AND status=1')->get();
        $payinChart = DB::table('investment_logs')->select('amount', DB::raw('DAYOFWEEK(created_at) as weekday'))->whereRaw('WEEk(created_at) = WEEK(CURDATE()) AND status=2')->get();
        $sumPayout = Payouts::selectRaw("SUM(roi) AS sumpayout")->where('status', 1)->first();
        $sumPayin = DB::table('investment_logs')->selectRaw("SUM(amount) AS sumpayin")->where('status', 2)->first();
        $curMPout = Payouts::join('investments', 'payouts.investment_id', '=', 'investments.id')
                ->join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                ->select('traders.trader_type', 'payouts.roi')
                ->where('payouts.status', 1)
                ->whereRaw('MONTH(payouts.created_at) = MONTH(CURRENT_TIMESTAMP)')
                ->get();
        $curMPin = DB::table('investment_logs')->join('investments', 'investment_logs.investment_id', '=', 'investments.id')
                ->join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                ->select('traders.trader_type', 'investment_logs.amount')
                ->where('investment_logs.status', 2)
                ->whereRaw('MONTH(investment_logs.created_at) = MONTH(CURRENT_TIMESTAMP)')
                ->get();
        return view('admin.report_analysis', [
            'all_investors'         =>  $all_investors,
            'payments_total'        =>  $payments_total,
            'payments_today'        =>  $payments_today,
            'investment_total'      =>  $investment_total,
            'investment_active'     =>  $investment_active,
            'payouts_total'         =>  $payouts_total,
            'payouts_unconfirmed'   =>  $payouts_unconfirmed,
            'payoutChart'           =>  $payoutChart,
            'payinChart'            =>  $payinChart,
            'sumPayout'             =>  $sumPayout->sumpayout,
            'sumPayin'              =>  $sumPayin->sumpayin,
            'curMPout'              =>  $curMPout,
            'curMPin'               =>  $curMPin
        ]);
    }

    public function mReportAnalysis($filterMonth="", $filterYear="")
    {
        if ($filterMonth != "" && $filterYear != ""){
            $curMPout = Payouts::join('investments', 'payouts.investment_id', '=', 'investments.id')
                ->join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                ->select('traders.trader_type', 'payouts.roi')
                ->where('payouts.status', 1)
                ->whereRaw('YEAR(payouts.created_at) =' . $filterYear)
                ->whereRaw('MONTH(payouts.created_at) =' . $filterMonth)
                ->get();
            $curMPin = DB::table('investment_logs')->join('investments', 'investment_logs.investment_id', '=', 'investments.id')
                ->join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                ->select('traders.trader_type', 'investment_logs.amount')
                ->where('investment_logs.status', 2)
                #->whereRaw('YEAR(investment_logs.created_at) =' . $filterYear)
                ->whereRaw('MONTH(investment_logs.created_at) =' . $filterMonth)
                ->get();
            return response()->json([
                'curMPout'  =>  $curMPout,
                'curMPin'   =>  $curMPin
            ]);
        }
    }

    public function payoutList()
    {
        $tradersToPay = Investments::join('payouts', 'payouts.investment_id', '=', 'investments.id')
                    ->join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                    ->join('bank_accounts', 'traders.trader_id', '=', 'bank_accounts.trader_id')
                    ->where('payouts.status', 0)
                    ->select('payouts.*', 'investments.amount', 'investments.monthly_pcent',
                            'investments.duration', 'investments.start_date', 'investments.end_date', 'traders.trader_id',
                            'traders.full_name', 'bank_accounts.bank_name',
                            DB::raw('LPAD(bank_accounts.account_number, 10, 0) AS account_number'))
                    ->orderBy('payouts.id')
                    ->paginate(10);
        return view('admin.payout_list', ['tradersToPay' => $tradersToPay]);
    }

    public function authPayout(Request $request)
    {
        $admin = auth()->user()->username;
        $payoutId = $request->payout_id;
        DB::table('payouts')->where('id', $payoutId)->update([
            'status' => 1,
            'admin' => $admin,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $payout = DB::table('payouts')->find($payoutId);
        $investment = Investments::find($payout->investment_id);
        $trader = Traders::select('email')->where('trader_id', $investment->trader_id)->first();
        Mail::to($trader->email)->send(new SendPayoutMail($investment->amount, $payout->roi));
        return redirect()->back();
    }

    public function exportPayout()
    {
        $filename = 'TradersToPay-'.date('Y-m-d').'.xlsx';
        return Excel::download(new TradersToPayExport, $filename);
    }

    public function register()
    {
        $users =  User::all();
        return view('admin.register', ['users' => $users]);
    }

    public function payoutConfirmed()
    {
        $tradersToPay = Investments::join('payouts', 'payouts.investment_id', '=', 'investments.id')
                    ->join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                    ->join('bank_accounts', 'traders.trader_id', '=', 'bank_accounts.trader_id')
                    ->where('payouts.status', 1)
                    ->select('payouts.*', 'investments.amount', 'investments.monthly_pcent',
                            'investments.duration', 'investments.start_date', 'investments.end_date', 'traders.trader_id',
                            'traders.full_name', 'bank_accounts.bank_name',
                            DB::raw('LPAD(bank_accounts.account_number, 10, 0) AS account_number'))
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        return view('admin.payout_confirmed', ['tradersToPay' => $tradersToPay]);
    }

    public function searchPayoutConfirmed(Request $request)
    {
        $trader_id = $request->trader_id;
        $date = $request->payDate;
        if ($trader_id != "" && $date != "") {
            $paidTraders = Investments::join('payouts', 'payouts.investment_id', '=', 'investments.id')
                    ->join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                    ->join('bank_accounts', 'traders.trader_id', '=', 'bank_accounts.trader_id')
                    ->where([
                        ['payouts.status', 1],
                        ['traders.trader_id', $trader_id],
                        [DB::raw('DATE(payouts.updated_at)'), $date]
                    ])
                    ->select('traders.trader_id', 'traders.full_name', DB::raw('LPAD(bank_accounts.account_number, 10, 0) AS account_number'),
                            'bank_accounts.bank_name', 'investments.amount', 'payouts.roi', 'investments.monthly_pcent',
                            'investments.duration', 'investments.start_date', 'investments.end_date', 'payouts.admin',
                            'payouts.updated_at')
                    ->orderBy('updated_at', 'desc')
                    ->take(20)
                    ->get();
            return response()->json([
                'paidTraders' => $paidTraders
            ]);
        }
        $paidTraders = Investments::join('payouts', 'payouts.investment_id', '=', 'investments.id')
                    ->join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                    ->join('bank_accounts', 'traders.trader_id', '=', 'bank_accounts.trader_id')
                    ->where([
                        ['payouts.status', 1],
                        ['traders.trader_id', $trader_id]
                    ])
                    ->orWhere([
                        ['payouts.status', 1],
                        [DB::raw('DATE(payouts.updated_at)'), $date]
                    ])
                    ->select('traders.trader_id', 'traders.full_name', DB::raw('LPAD(bank_accounts.account_number, 10, 0) AS account_number'),
                            'bank_accounts.bank_name', 'investments.amount', 'payouts.roi', 'investments.monthly_pcent',
                            'investments.duration', 'investments.start_date', 'investments.end_date', 'payouts.admin',
                            'payouts.updated_at')
                    ->orderBy('updated_at', 'desc')
                    ->take(20)
                    ->get();
        return response()->json([
            'paidTraders' => $paidTraders
        ]);
    }
}

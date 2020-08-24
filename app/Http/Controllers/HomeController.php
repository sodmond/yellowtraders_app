<?php

namespace App\Http\Controllers;

use App\Investments;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPayoutMail;
use App\Traders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function register()
    {
        return view('admin.register');
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
        $payments_today = DB::table('received_payments')->where(DB::raw('date(created_at)'), DB::raw('CURDATE()'))->count();
        $investment_total = Investments::count();
        $investment_active = Investments::where('status', 2)->count();
        $payouts_total = DB::table('payouts')->count();
        $payouts_unconfirmed = DB::table('payouts')->where('status', 0)->count();
        return view('admin.dashboard', [
            'tradersToPay'          =>  $tradersToPay,
            'all_investors'         =>  $all_investors,
            'payments_total'        =>  $payments_total,
            'payments_today'        =>  $payments_today,
            'investment_total'      =>  $investment_total,
            'investment_active'     =>  $investment_active,
            'payouts_total'         =>  $payouts_total,
            'payouts_unconfirmed'   =>  $payouts_unconfirmed
        ]);
    }

    public function payoutList()
    {
        $tradersToPay = Investments::join('payouts', 'payouts.investment_id', '=', 'investments.id')
                    ->join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                    ->join('bank_accounts', 'traders.trader_id', '=', 'bank_accounts.trader_id')
                    ->where('payouts.status', 0)
                    ->select('payouts.*', 'investments.amount', 'investments.monthly_pcent',
                            'investments.duration', 'investments.start_date', 'investments.end_date', 'traders.trader_id',
                            'traders.full_name', 'bank_accounts.bank_name', 'bank_accounts.account_number')
                    ->orderBy('payouts.id')
                    ->paginate(15);
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
        $investment = Investments::find($payout->investment);
        $trader = Traders::select('email')->where('trader_id', $investment->trader_id)->first();
        Mail::to($trader->email)->send(new SendPayoutMail($investment->amount, $payout->roi));
        return redirect()->back();
    }

    public function exportPayout()
    {
        $tradersToPay = Investments::join('payouts', 'payouts.investment_id', '=', 'investments.id')
                    ->join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                    ->join('bank_accounts', 'traders.trader_id', '=', 'bank_accounts.trader_id')
                    ->where('payouts.status', 0)
                    ->select('traders.full_name', 'investments.amount', 'payouts.roi', 'investments.monthly_pcent',
                            'bank_accounts.bank_name', 'bank_accounts.account_number', 'investments.duration')
                    ->orderBy('payouts.id')
                    ->get();
        $header = [
            'Customername',
            'Amount Invested',
            'Monthly ROI',
            'Monthly %',
            'Bankname',
            'Account Number',
            'Duration'
        ];
        $filename = 'TradersToPay-'.date("Y-m-d").'.csv';
        return new StreamedResponse(function() use ($tradersToPay, $header){
            $output = fopen('php://output', 'w');
            fputcsv($output, $header);
            foreach ($tradersToPay as $payout) {
                $row = [
                    $payout->full_name, $payout->amount, $payout->monthly->roi, $payout->monthly_pcent,
                    $payout->bankname, $payout->account_number, $payout->duration
                ];
                fputcsv($output, $row);
            }
        }, 200,[
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$filename
        ]);
        #return view('admin.payout_export', ['tradersToPay' => $tradersToPay]);
    }
}

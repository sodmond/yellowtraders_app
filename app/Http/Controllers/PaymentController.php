<?php

namespace App\Http\Controllers;

use App\Investments;
use App\Mail\SendReceivedConfirmation;
use App\Traders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function recieved_payments()
    {
        $r_pay = DB::table('received_payments')
                ->join('investment_logs', 'received_payments.investment_log_id', '=', 'investment_logs.id')
                ->join('investments', 'investment_logs.investment_id', '=', 'investments.id')
                ->select('received_payments.id', 'received_payments.created_at', 'received_payments.investment_log_id', 'investment_logs.investment_type', 'investment_logs.amount', 'investments.trader_id')
                ->where('received_payments.status', 1)
                ->paginate(15);

        $all_pay = DB::table('received_payments')
                ->join('investment_logs', 'received_payments.investment_log_id', '=', 'investment_logs.id')
                ->join('investments', 'investment_logs.investment_id', '=', 'investments.id')
                ->select('received_payments.id', 'received_payments.created_at', 'received_payments.investment_log_id', 'received_payments.admin', 'investment_logs.investment_type', 'investment_logs.amount', 'investments.trader_id')
                ->paginate(10);
        return view('admin.payments', ['r_pay' => $r_pay, 'all_pay' => $all_pay]);
    }

    public function viewPayment($id)
    {
        $payment = DB::table('received_payments')
                ->join('investment_logs', 'received_payments.investment_log_id', '=', 'investment_logs.id')
                ->join('investments', 'investment_logs.investment_id', '=', 'investments.id')
                ->join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                ->join('bank_accounts', 'investments.trader_id', '=', 'bank_accounts.trader_id')
                ->select('received_payments.*', 'investment_logs.investment_type', 'investment_logs.amount',
                'investments.trader_id', 'traders.full_name', 'bank_accounts.bank_name', 'bank_accounts.account_number')
                ->where('received_payments.id', $id)
                ->first();

        return view('admin.payments_auth', ['payment' => $payment]);
    }

    public function authPayment(Request $request)
    {
        $payId = $request->payId;
        $logId = $request->logId;
        $authType = $request->authType;
        $inv_type = $request->inv_type;
        $getInv = DB::table('investment_logs')->select('investment_id', 'amount')->where('id', $logId)->first();
        $invId = $getInv->investment_id;
        $inv = Investments::where('id', $invId)->first();
        $dur = $inv->duration;
        $start = $request->start_date;
        $newDate = date_create($start);
        date_add($newDate, date_interval_create_from_date_string("$dur months"));
        $end = date_format($newDate,"Y-m-d");
        $invConvert = json_decode(json_encode($inv), true);
        $getEmail = Traders::select('email')->where('trader_id', $inv->trader_id)->first();
        $email = $getEmail->email;
        if($authType == "confirm" && $inv_type != "topup"){
            DB::beginTransaction();
            DB::table('received_payments')->where('id', $payId)->update(['status' => 2, 'admin' => auth()->user()->username, 'updated_at' => date('Y-m-d H:i:s')]);
            DB::table('investment_logs')->where('id', $logId)->update(['status' => 2, 'updated_at' => date('Y-m-d H:i:s')]);
            Investments::where('id', $invId)->update(['start_date'=>$start, 'end_date'=>$end, 'status'=>2, 'updated_at' => date('Y-m-d H:i:s')]);
            DB::commit();
            Mail::to($email)->send(new SendReceivedConfirmation($invConvert, 'new'));
            return redirect('/admin/payments/'.$payId.'?msg=success');
        }
        if ($authType == "confirm" && $inv_type == "topup") {
            $old_amount = Investments::where('id', $getInv->investment_id)->first('amount');
            $new_amount = $old_amount->amount + $getInv->amount;
            $numToWord = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
            $amountwords = $numToWord->format($new_amount);
            $monthly_pcent = DB::table('monthly_rois')->select('per_cent')->where([
                ['min', '<=', $new_amount],
                ['max', '>=', $new_amount],
            ])->first();
            $monthly_roi = ($new_amount * $monthly_pcent->per_cent) / 100;
            DB::beginTransaction();
            DB::table('received_payments')->where('investment_log_id', $logId)->update(['status' => 2, 'admin' => auth()->user()->username, 'updated_at' => date('Y-m-d H:i:s')]);
            DB::table('investment_logs')->where('id', $logId)->update(['status' => 2, 'updated_at' => date('Y-m-d H:i:s')]);
            $topupInv = [
                'amount' => $new_amount,
                'amount_in_words' => $amountwords,
                'monthly_roi' => $monthly_roi,
                'monthly_pcent' => $monthly_pcent->per_cent,
                'status' => 2,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            Investments::where('id', $invId)->update($topupInv);
            DB::commit();
            Mail::to($email)->send(new SendReceivedConfirmation($invConvert, 'topup', $topupInv));
            return redirect('/admin/payments/'.$payId.'?msg=success');
        }
        if ($authType == "reject" && $inv_type != "topup") {
            DB::beginTransaction();
            DB::table('received_payments')->where('investment_log_id', $logId)->update(['status' => 0, 'admin' => auth()->user()->username, 'updated_at' => date('Y-m-d H:i:s')]);
            DB::table('investment_logs')->where('id', $logId)->update(['status' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
            Investments::where('id', $invId)->update(['status' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
            DB::commit();
            #Mail::to($data['email'])->send(new SendApplication($data, $bank, $investment));
            return redirect('/admin/payments/'.$payId.'?msg=error');
        }
        if ($authType == "reject" && $inv_type == "topup") {
            DB::beginTransaction();
            DB::table('received_payments')->where('investment_log_id', $logId)->update(['status' => 0, 'admin' => auth()->user()->username, 'updated_at' => date('Y-m-d H:i:s')]);
            DB::table('investment_logs')->where('id', $logId)->update(['status' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
            DB::commit();
            #Mail::to($data['email'])->send(new SendApplication($data, $bank, $investment));
            return redirect('/admin/payments/'.$payId.'?msg=error');
        }
        return redirect('/admin/payments');
    }
}

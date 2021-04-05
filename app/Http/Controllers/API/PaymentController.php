<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Investments;
use App\Mail\SendCapitalApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function paymentProof(Request $request)
    {
        $this->validate($request, [
            'trans_num' => 'required|min:15|max:32',
            'proof' => 'required|mimes:png,jpg,jpeg|max:2048'
        ],[
            'proof.mimes' => 'Payment proof must be either JPEG or PNG format'
        ]);
        $getLog = explode("#", $request->trans_num);
        $logId = ltrim($getLog[1], "0");
        $invLog = DB::table('investment_logs')->find($logId);
        if (!$invLog) {
            return response()->json(['message' => 'Invalid transaction ID.'], 200);
        }
        $investment = Investments::select('trader_id')->find($invLog->investment_id);
        $logCheck = DB::table('received_payments')->where('investment_log_id', $logId)->first();
        if($investment->trader_id == auth()->user()->username && $invLog->status == 1 && $logCheck == ""){
            $proof = Storage::putFile('payments_proof', $request->file('proof'));
            $data = [
                'investment_log_id' => $invLog->id,
                'payment_proof' => $proof,
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
            ];
            DB::table('received_payments')->insert($data);
            return response()->json(['message' => 'Payment Proof has been submitted'], 200);
        }
        return response()->json(['message' => 'Invalid credentials or Expired transaction ID'], 200);
    }

    public function capitalWithdrawal(Request $request)
    {
        if( isset($request->cwForm) ){
            if ($request->cwForm != "available") {
                return response()->json(['message' => 'Capital withdrawal is not available for you now.'], 200);
            }
            $this->validate($request, [
                'amount'        => 'required|numeric',
                'bank_stmt'     => 'required|mimes:pdf|max:5000',
            ],[
                'bank_stmt.mimes'           => 'Bank Statement must be in a PDF format',
                'bank_stmt.uploaded'        => 'Bank Statement file size cannot exceed 5MB'
            ]);
            $bankpath = Storage::putFile('bank_stmt', $request->file('bank_stmt'));
            $data = [
                'trader_id'     => auth()->user()->username,
                'amount'        => $request->amount,
                'bank_stmt'     => $bankpath,
                'created_at'    => date('Y-m-d H:i:s'),
            ];
            $getBank = DB::table('bank_accounts')->select('bank_name', 'account_number')
                        ->where('trader_id', auth()->user()->username)->first();
            DB::beginTransaction();
            $addCapWit = DB::table('capital_request')->insert($data);
            if ($addCapWit) {
                DB::commit();
                Mail::to(auth()->user()->email)->cc('admin@yellowtraders.org')->queue(new SendCapitalApplication(
                    auth()->user()->username,
                    auth()->user()->name,
                    $request->amount,
                    $request->amount_words,
                    $getBank->bank_name,
                    $getBank->account_number,
                    $bankpath
                ));
                return response()->json(['message' => 'Your capital withdrawal application has been submitted.'], 200);
            }
            DB::rollBack();
            return response()->json(['message' => 'A problem occured, please try again.'], 500);
        }
        $getInv = Investments::where('trader_id', auth()->user()->username)->select('status', 'capital')->first();
        if ($getInv->capital == 0) {
            $pend_msg = "Your account has been archived, pls contact customer care for re-activation";
            return response()->json(['message' => $pend_msg], 200);
        }
        if ($getInv->status != 0){
            $msg = 'Capital withdrawal is not available at the moment because you have an active investment.';
            return response()->json(['message' => $msg], 200);
        }
        return response()->json([
            'message' => 'You can request for you capital now.',
            'cwForm' => 'available'
        ], 200);
    }
}

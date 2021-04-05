<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvLogCollection;
use App\Investments;
use App\Http\Resources\TraderResource;
use App\Mail\SendTransaction;
use App\User;
use App\Traders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{

    public function getROI_duration($amount){
        $duroi = new Investments;
        return response()->json(['data' => $duroi->calRoi($amount)], 200);
    }

    public function activeInv()
    {
        $actInv = Investments::where('trader_id', auth()->user()->username)
                ->select('amount', 'amount_in_words', 'monthly_roi', 'monthly_pcent', 'duration', 'purpose', 'start_date', 'end_date', 'status')
                ->first();
        if ($actInv->status != 2) {
            return response()->json(['message' => 'You do not have an active investment'], 200);
        }
        $changeStatus = new Investments;
        return response()->json(['data' => $changeStatus->repInvStatus($actInv)], 200);
    }

    public function invLog()
    {
        $actInv = Investments::select('id')->where('trader_id', auth()->user()->username)->first();
        $invtmntLogs = DB::table('investment_logs')->where('investment_id', $actInv->id)
                    ->select('investment_type', 'amount', 'amount_in_words', 'monthly_roi', 'monthly_pcent', 'duration', 'purpose', 'created_at')
                    ->paginate(10);
        return new TraderResource($invtmntLogs);
        //return new InvLogCollection(User::paginate(10));
    }

    public function topup(Request $request)
    {
        $getInv = Investments::join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                            ->select('investments.id', 'investments.status', 'investments.duration', 'investments.capital', 'traders.trader_type')
                            ->where('investments.trader_id', auth()->user()->username)
                            ->first();
        $status = $getInv->status;
        $investment = new Investments();
        if ($getInv->capital == 0) {
            $pend_msg = "Your account has been archived, pls contact customer care for re-activation";
            return response()->json([
                'message' => $pend_msg,
                'topupStatus' => 'unavailable'
            ], 200);
        }
        if ($status != 2){
            return response()->json([
                'message' => 'You cannot top up at this moment.',
                'topupStatus' => 'unavailable'
            ], 200);
        }
        if (isset($request->topupStatus)) {
            if ($request->topupStatus != "available") {
                return response()->json([
                    'message' => 'Topup is not available for you at the moment.'], 200);
            }
            $this->validate($request, [
                'amount' => ['required', 'numeric',
                function($attribute, $value, $fail) use($getInv, $investment){
                    $msg = $investment->valAmount($value, $getInv->trader_type, 1);
                    if ($msg != 'valid') {
                        $fail($msg);
                    }
                }],
                'amount_words' => 'required|max:255',
                'purpose' => 'nullable|max:255',
            ]);
            $monthly_pcent = DB::table('monthly_rois')->select('per_cent')->where([
                ['min', '<=', $request->amount],
                ['max', '>=', $request->amount],
            ])->get();
            $monthly_roi = ($request->amount * $monthly_pcent[0]->per_cent) / 100;
            $data = [
                'amount' => $request->amount,
                'amount_in_words' => $request->amount_words,
                'monthly_roi' => $monthly_roi,
                'monthly_pcent' => $monthly_pcent[0]->per_cent,
                'purpose' => $request->purpose,
                'status' => '1',
            ];
            $logId = $investment->logInv($data, $getInv->id, 'topup');
            Mail::to(auth()->user()->email)->queue(new SendTransaction(auth()->user()->username, $request->amount, $logId, "top up"));
            return response()->json(['message' => 'Your top up request has been submitted successfully'], 200);
        }
        $topupDays = [];
        $getInvLog = DB::table('investment_logs')->select('created_at', 'status')
                        ->where('investment_id', $getInv->id)
                        ->orderBy('created_at', 'desc')
                        ->first();
        $lastLogDate = strtotime($getInvLog->created_at);
        for ($i=1; $i<$getInv->duration ; $i++) {
            $topupInv = Investments::select(DB::raw('DATE_ADD(start_date, INTERVAL '.$i.' MONTH) AS s_date'),
                            DB::raw('DATEDIFF(CURRENT_DATE, DATE_ADD(start_date, INTERVAL '.$i.' MONTH)) AS daynum'))
                            ->where('id', $getInv->id)
                            ->first();
            $days = $topupInv->daynum;
            $topupStart = strtotime($topupInv->s_date);
            if ($days < 1 || $days > 10){
                $topupDays[] = 0;
            }
            if ($days > 0 && $days <= 10 && $topupStart > $lastLogDate){
                $topupDays[] = 1;
            }
            if ($days > 0 && $days <= 10 && $lastLogDate > $topupStart && $getInvLog->status == 0 ){
                $topupDays[] = 1;
            }
        }
        if ( !(in_array(1, $topupDays)) ) {
            return response()->json([
                'message' => 'Topup is not available for you at the moment.',
                'topupStatus' => 'unavailable'
            ], 200);
        }
        return response()->json([
            'message' => 'Topup is available now.',
            'topupStatus' => 'available'
        ], 200);
    }

    public function rollover(Request $request)
    {
        $getInv = Investments::join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                            ->select('investments.id', 'investments.status', 'investments.duration', 'investments.capital', 'traders.trader_type')
                            ->where('investments.trader_id', auth()->user()->username)
                            ->first();
        $status = $getInv->status;
        $investment = new Investments();
        if ($getInv->capital == 0) {
            $pend_msg = "Your account has been archived, pls contact customer care for re-activation";
            return response()->json([
                'message' => $pend_msg,
                'rolloverStatus' => 'unavailable'
            ], 200);
        }
        if ($status != 0){
            return response()->json([
                'message' => 'Oops! You have an active/pending investment at the moment.',
                'rolloverStatus' => 'unavailable'
            ], 200);
        }
        if (isset($request->rolloverStatus)) {
            if ($request->rolloverStatus != "available") {
                return response()->json([
                    'message' => 'You cannot rollover at the moment.',
                    'rolloverStatus' => 'unavailable'
                ], 200);
            }
            $this->validate($request, [
                'amount' => ['required', 'numeric',
                function($attribute, $value, $fail) use($getInv, $investment){
                    $msg = $investment->valAmount($value, $getInv->trader_type);
                    if ($msg != 'valid') {
                        $fail($msg);
                    }
                }],
                'amount_words' => 'required|max:255',
                'duration' => 'required|numeric',
                'purpose' => 'nullable|max:255',
            ]);
            $monthly_pcent = DB::table('monthly_rois')->select('per_cent')->where([
                ['min', '<=', $request->amount],
                ['max', '>=', $request->amount],
            ])->get();
            $monthly_roi = ($request->amount * $monthly_pcent[0]->per_cent) / 100;
            $data = [
                'amount' => $request->amount,
                'amount_in_words' => $request->amount_words,
                'monthly_roi' => $monthly_roi,
                'monthly_pcent' => $monthly_pcent[0]->per_cent,
                'duration' => $request->duration,
                'purpose' => $request->purpose,
                'status' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $logId = $investment->logInv($data, $getInv->id, 'rollover');
            Investments::where('trader_id', auth()->user()->username)->update($data);
            Mail::to(auth()->user()->email)->queue(new SendTransaction(auth()->user()->username, $request->amount, $logId, 'rollover'));
            return response()->json(['message' => 'Your rollover request has been submitted successfully'], 200);
        }
        return response()->json([
            'message' => 'Rollover is available now.',
            'rolloverStatus' => 'available'
        ], 200);
    }
}

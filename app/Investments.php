<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Investments extends Model
{
    protected $fillable = [
        'trader_id',
        'amount', 'amount_in_words', 'monthly_roi', 'monthly_pcent',
        'duration', 'purpose', 'start_date', 'end_date',
        'status',
    ];

    public function repInvStatus($inv)
    {
        $convertInv = [];
        switch ($inv->status) {
            case 1:
                $convertInv = array_replace(json_decode(json_encode($inv), true), ['status' => 'pending']);
                break;
            case 2:
                $convertInv = array_replace(json_decode(json_encode($inv), true), ['status' => 'active']);
                break;
            default:
                $convertInv = array_replace(json_decode(json_encode($inv), true), ['status' => 'inactive']);
                break;
        }
        return $convertInv;
    }

    public function valAmount($amount, $trader_type, $topup = 0)
    {
        if ($trader_type == 1 && $amount < 150000) {
            return 'Investment amount cannot be less than 150,000';
        }
        if ($trader_type == 2 && $amount < 50000) {
            return 'Investment amount cannot be less than 50,000';
        }
        if ($trader_type == 3 && $amount < 1000000 && $topup == 1) {
            return 'Investment amount cannot be less than 1,000,000';
        }
        if ($trader_type == 3 && $amount < 2000000 && $topup == 0) {
            return 'Investment amount cannot be less than 2,000,000';
        }
        return 'valid';
    }

    public function logInv($data, $inv_id, $inv_type)
    {
        DB::beginTransaction();
        array_pop($data);
        $newData = array_merge_recursive(
            ['investment_id'=>$inv_id, 'investment_type'=>$inv_type],
            $data,
            ['created_at'=>date('Y-m-d H:i:s')]
        );
        $sql = DB::table('investment_logs')->insertGetId($newData);
        if ($sql){
            DB::commit();
            return $sql;
        }
        DB::rollBack();
        return "";
    }

    public function calRoi($amount)
    {
        $dur = Traders::join('trader_types', 'traders.trader_type', '=', 'trader_types.id')
                        ->where('traders.trader_id', auth()->user()->username)
                        ->select('durations')->first();
        $durs = explode(",", $dur->durations);
        if($amount > 30000000){
            return response()->json(['monthly_pcent'=>'negotiable', 'monthly_roi'=>'negotiable']);
        }
        $get_pcent = DB::table('monthly_rois')->select('per_cent')->where([
            ['min', '<=', $amount],
            ['max', '>=', $amount],
        ])->get();
        $ck_pcent = isset($get_pcent[0]->per_cent) ? $get_pcent[0]->per_cent : 0;
        $get_roi = ($amount * $ck_pcent) / 100;
        $monthly_pcent = $ck_pcent;
        $monthly_roi = $get_roi;
        $numToWord = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
        $amountwords = $numToWord->format($amount);
        #return response()->json();
        return [
            'monthly_pcent' =>  $monthly_pcent,
            'monthly_roi'   =>  $monthly_roi,
            'amountwords'   =>  $amountwords,
            'durations'     =>  $durs
        ];
    }
}

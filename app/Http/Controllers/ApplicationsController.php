<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traders;
use App\Investments;
use Dotenv\Validator;
use Error;
use Redirect, Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendApplication;
use App\Mail\SendTransaction;

class ApplicationsController extends Controller
{
    public function __construct() {
        $rand1 = rand(5, 99);
        $rand2 = rand(10, 99999);
        $rand = ($rand1 . $rand2 . date('s'));
        $num = $rand;
        if(strlen($rand) < 6){
            $num = str_pad($rand, 6, 0, STR_PAD_LEFT);
        }
        $trader_id = "inv_".$num;
        $this->trader_id = $trader_id;
    }

    public function calRoi($amount)
    {
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
        return response()->json(['monthly_pcent'=>$monthly_pcent, 'monthly_roi'=>$monthly_roi]);
    }
    private function valDOB($date)
    {
        $cur_date = strtotime(date('Y-m-d'));
        $new_date = strtotime($date);
        if ( ($cur_date - $new_date) > 0 ){
            $date_diff = abs($cur_date - $new_date);
            $years = floor($date_diff / (365*60*60*24));
            return $years;
        }
        return 'Error';
    }
    private function valAmount($amount, $trader_type, $topup = "")
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
        if ($trader_type == 3 && $amount < 2000000) {
            return 'Investment amount cannot be less than 2,000,000';
        }
        return 'valid';
    }
    private function valBankInvestment(Request $request, array $data, string $inv_type)
    {
        $this->validate($request, [
            'bank_name' => 'required|max:255',
            'account_number' => 'required|numeric|unique:bank_accounts,account_number',
            'account_name' => 'required|max:255',
            'amount' => ['required', 'numeric',
            function($attribute, $value, $fail) use($data){
                $msg = $this->valAmount($value, $data['trader_type']);
                if ($msg != 'valid') {
                    $fail($msg);
                }
            }],
            'amount_words' => 'required|max:255',
            'duration' => 'required|numeric',
            'purpose' => 'nullable|max:255',
        ]);
        $bank = [
            'trader_id' => $this->trader_id,
            'bank_name' => $request->bank_name,
            'holder_name' => $request->account_name,
            'account_number' => $request->account_number,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $monthly_pcent = 0;
        $monthly_roi = 0;
        if ($data['trader_type'] != 2 && $request->amount <= 30000000){
            $get_pcent = DB::table('monthly_rois')->select('per_cent')->where([
                ['min', '<=', $request->amount],
                ['max', '>=', $request->amount],
            ])->first();
            $monthly_pcent = $get_pcent->per_cent;
            $monthly_roi = ($request->amount * $monthly_pcent) / 100;
        }
        if ($data['trader_type'] == 2){
            $monthly_pcent = 10;
            $monthly_roi = ($request->amount * 10) / 100;
        }
        $investment = [
            'trader_id' => $this->trader_id,
            'amount' => $request->amount,
            'amount_in_words' => $request->amount_words,
            'monthly_roi' => $monthly_roi,
            'monthly_pcent' => $monthly_pcent,
            'duration' => $request->duration,
            'purpose' => $request->purpose,
            'status' => '1',
        ];
        DB::beginTransaction();
        $new_bank = DB::table('bank_accounts')->insert($bank);
        $new_inv = Investments::create($investment);
        array_pop($investment);
        array_shift($investment);
        array_pop($investment);
        $investment1 = array_merge_recursive([
            'investment_id' => $new_inv->id,
            'investment_type' => $inv_type,
            ], $investment,
            ['created_at' => date('Y-m-d H:i:s')
        ]);
        $inv_log = DB::table('investment_logs')->insertGetId($investment1);
        $trader = Traders::create($data);
        if ($new_bank && $new_inv && $inv_log && $trader) {
            DB::commit();
            Mail::to($data['email'])->send(new SendApplication($data, $bank, $investment));
            Mail::to($data['email'])->send(new SendTransaction($this->trader_id, $request->amount, $inv_log, 'new'));
            return true;
        }
        DB::rollBack();
        return false;
    }

    private function storeImage($image)
    {
        $path = Storage::putFile('passports', $image);
        return $path;
    }

    public function yellowTraders()
    {
        return view('apply.yellow_traders');
    }

    public function yellowTradersVal(Request $request)
    {
        $this->validate($request, [
            'fname' => 'required|max:255',
            'email' => 'required|max:255|email|unique:traders,email',
            'gender' => 'required|max:255',
            'marital' => 'required|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|numeric|unique:traders,phone',
            'alt_phone' => 'nullable',
            'dob' => ['required', 'max:255', 'date',
            function ($attribute, $value, $fail){
                $age = $this->valDOB($value);
                if ($age == 'Error' || $age < 18){
                    $fail('Only Age 18 years and above are allowed to register here');
                }
            }],
            'country' => 'required|max:255',
            'state' => 'required|max:255',
            'lga' => 'required|max:255',
            'nok_name' => 'required|max:255',
            'nok_phone' => 'required|numeric',
            'image.*' => 'required|mimes:png,jpg,jpeg|size:5000',
            'ref' => 'max:255|nullable',
        ]);

        $trader_type = "1";
        $inv_type = "new";
        $image = $this->storeImage($request->file('image'));
        $data = [
            'trader_id' => $this->trader_id,
            'trader_type' => $trader_type,
            'full_name' => $request->fname,
            'marital_status' => $request->marital,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone' => $request->phone,
            'other_phone' => $request->alt_phone,
            'dob' => $request->dob,
            'nationality' => $request->country,
            'state' => $request->state,
            'lga' => $request->lga,
            'email' => $request->email,
            'image' => $image,
            'contact_name' => $request->nok_name,
            'contact_phone' => $request->nok_phone,
            'referral' => $request->ref,
        ];
        $newTrader = $this->valBankInvestment($request, $data, $inv_type);
        $suc_msg = "Your information has been submitted";
        $err_msg = "Error submitting your information. Please try again";
        #($newTrader == true) ? dd("Succesfully saved") : dd("Error saving trader");
        return  ($newTrader == true) ? view('apply.payment', ['suc_msg' => $suc_msg]) : view('apply.yellow_traders', ['err_msg' => $err_msg]);
    }

    public function juniorTraders()
    {
        return view('apply.junior_traders');
    }
    public function juniorTradersVal(Request $request)
    {
        $this->validate($request, [
            'fname' => 'required|max:255',
            'email' => 'required|max:255|email|unique:traders,email',
            'gender' => 'required|max:255',
            'marital' => 'required|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|numeric|unique:traders,phone',
            'dob' => ['required', 'max:255', 'date',
            function ($attribute, $value, $fail){
                $age = $this->valDOB($value);
                if ($age == 'Error' || $age > 17){
                    $fail('Only age 0-17 are allowed to register here');
                }
            }],
            'country' => 'required|max:255',
            'state' => 'required|max:255',
            'lga' => 'required|max:255',
            'p_name' => 'required|max:255',
            'p_phone' => 'required|numeric',
            'image.*' => 'required|mimes:png,jpg,jpeg|max:5000',
            'ref' => 'max:255|nullable',
        ]);

        $trader_type = "2";
        $inv_type = "new";
        $image = $this->storeImage($request->file('image'));
        $data = [
            'trader_id' => $this->trader_id,
            'trader_type' => $trader_type,
            'full_name' => $request->fname,
            'marital_status' => $request->marital,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'nationality' => $request->country,
            'state' => $request->state,
            'lga' => $request->lga,
            'email' => $request->email,
            'image' => $image,
            'contact_name' => $request->p_name,
            'contact_phone' => $request->p_phone,
            'referral' => $request->ref,
        ];
        $newTrader = $this->valBankInvestment($request, $data, $inv_type);
        $suc_msg = "Your information has been submitted";
        $err_msg = "Error submitting your information. Please try again";
        #($newTrader == true) ? dd("Succesfully saved") : dd("Error saving trader");
        return  ($newTrader == true) ? view('apply.payment', ['suc_msg' => $suc_msg]) : view('apply.junior_traders', ['err_msg' => $err_msg]);
    }

    public function corporateTraders()
    {
        return view('apply.corporate_traders');
    }
    public function corporateTradersVal(Request $request)
    {

        $this->validate($request, [
            'cname' => 'required|max:255',
            'email' => 'required|max:255|email|unique:traders,email',
            'address' => 'required|max:255',
            'phone' => 'required|numeric|unique:traders,phone',
            'alt_phone' => 'nullable',
            'dob' => ['required', 'max:255', 'date',
            function ($attribute, $value, $fail){
                if ($this->valDOB($value) == 'Error'){
                    $fail("You can't use a date of incorporation in the future");
                }
            }],
            'country' => 'required|max:255',
            'state' => 'required|max:255',
            'city' => 'required|max:255',
            'rep_name' => 'required|max:255',
            'rep_phone' => 'required|numeric',
            'image.*' => 'required|mimes:png,jpg,jpeg|max:5000',
            'ref' => 'max:255|nullable',
        ]);

        $trader_type = "3";
        $inv_type = "new";
        $image = $this->storeImage($request->file('image'));
        $data = [
            'trader_id' => $this->trader_id,
            'trader_type' => $trader_type,
            'full_name' => $request->cname,
            'marital_status' => 'Not applicable',
            'gender' => 'Not applicable',
            'address' => $request->address,
            'phone' => $request->phone,
            'other_phone' => $request->alt_phone,
            'dob' => $request->dob,
            'nationality' => $request->country,
            'state' => $request->state,
            'lga' => $request->city,
            'email' => $request->email,
            'image' => $image,
            'contact_name' => $request->rep_name,
            'contact_phone' => $request->rep_phone,
            'referral' => 'none',
        ];
        $newTrader = $this->valBankInvestment($request, $data, $inv_type);
        $suc_msg = "Your information has been submitted";
        $err_msg = "Error submitting your information";
        #($newTrader == true) ? dd("Succesfully saved") : dd("Error saving trader");
        return  ($newTrader == true) ? view('apply.payment', ['suc_msg' => $suc_msg]) : view('apply.corporate_traders', ['err_msg' => $err_msg]);
    }

    # Topup and Rollover Form functions start here

    public function tuRoHome()
    {
        return view('apply.topup_rollover');
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

    public function tuRoHomeVal(Request $request)
    {
        if ($request->turo == "checkRec"){
            $this->validate($request, [
                'inv_type' => 'required|max:8|string',
                'tidpne' => 'required|max:32|string',
            ]);
            $trader = Traders::where('trader_id', $request->tidpne)
                        ->orWhere('phone', $request->tidpne)
                        ->orWhere('email', $request->tidpne)
                        ->get();
            if (count($trader) > 0) {
                $last_date = strtotime($trader[0]->updated_at);
                $cur_date = strtotime(date('Y-m-d H:i:s'));
                $date_diff = abs($cur_date - $last_date);
                $years = floor($date_diff / (365*60*60*24));
                $months = floor(($date_diff - $years * 365*60*60*24) / (30*60*60*24));
                $days = floor(($date_diff - $years * 365*60*60*24 -  $months*30*60*60*24)/ (60*60*24));
                $inv = Investments::where('trader_id', $trader[0]->trader_id)->get();
                $status = $inv[0]->status;
                $inv_type = "rollover";
                if ($status == 1){
                    $pend_msg = "You have a pending investment, check back later.";
                    return view('apply.topup_rollover', ['pend_msg' => $pend_msg]);
                }
                if ($status == 2 && $days <= 10){
                    $inv_type = "topup";
                }
                $trader_arr = json_decode(json_encode($trader[0]), true);
                return view('apply.topup_rollover', array_merge($trader_arr, ['inv_id' => $inv[0]->id,'inv_type' => $inv_type]));
            }
            $err_msg = "You need to register with us before you can Topup or Rollover";
            return view('apply.topup_rollover', ['err_msg' => $err_msg]);
        }
        if ($request->turo == "rollover"){
            $this->validate($request, [
                'amount' => ['required', 'numeric',
                function($attribute, $value, $fail) use($request){
                    $msg = $this->valAmount($value, $request->trader_type);
                    if ($msg != 'valid') {
                        $fail($msg);
                    }
                }],
                'amount_in_words' => 'required|max:255',
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
                'amount_in_words' => $request->amount_in_words,
                'monthly_roi' => $monthly_roi,
                'monthly_pcent' => $monthly_pcent[0]->per_cent,
                'duration' => $request->duration,
                'purpose' => $request->purpose,
                'status' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $logId = $this->logInv($data, $request->inv_id, $request->turo);
            Investments::where('trader_id', $request->trader_id)->update($data);
            $email = Traders::select('email')->where('trader_id', $request->trader_id)->first();
            Mail::to($email->email)->send(new SendTransaction($request->trader_id, $request->amount, $logId, 'rollover'));
            return view('apply.payment', ['suc_msg' => 'Your rollover request has been sent']);
        }
        if ($request->turo == "topup"){
            $this->validate($request, [
                'amount' => ['required', 'numeric',
                function($attribute, $value, $fail) use($request){
                    $msg = $this->valAmount($value, $request->trader_type, 1);
                    if ($msg != 'valid') {
                        $fail($msg);
                    }
                }],
                'amount_in_words' => 'required|max:255',
                'purpose' => 'nullable|max:255',
            ]);
            $old_amount = Investments::where('trader_id', $request->trader_id)->get('amount');
            $new_amount = $old_amount[0]->amount + $request->amount;
            $monthly_pcent = DB::table('monthly_rois')->select('per_cent')->where([
                ['min', '<=', $new_amount],
                ['max', '>=', $new_amount],
            ])->get();
            $monthly_roi = ($new_amount * $monthly_pcent[0]->per_cent) / 100;
            $data = [
                'amount' => $request->amount,
                'amount_in_words' => $request->amount_in_words,
                'monthly_roi' => $monthly_roi,
                'monthly_pcent' => $monthly_pcent[0]->per_cent,
                'purpose' => $request->purpose,
                'status' => '1',
                #'updated_at' => date('Y-m-d H:i:s'),
            ];
            $logId = $this->logInv($data, $request->inv_id, $request->turo);
            #array_shift($data);
            #Investments::where('trader_id', $request->trader_id)->increment('amount', $request->amount, $data);
            $email = Traders::select('email')->where('trader_id', $request->trader_id)->first();
            Mail::to($email->email)->send(new SendTransaction($request->trader_id, $request->amount, $logId, "top up"));
            return view('apply.payment', ['suc_msg' => 'Your top up request has been sent']);
        }
        return view('/apply/topup_rollover');
    }

    public function payment()
    {
        return view('apply.payment');
    }
    public function paymentVal(Request $request)
    {
        $this->validate($request, [
            'trans_num' => 'required|min:15|max:32|starts_with:trans#',
            'trader_num' => 'required|max:32|starts_with:inv_',
            'proof.*' => 'required|max:191|mimes:png,jpg,jpeg'
        ]);
        $getLog = explode("#", $request->trans_num);
        $logId = ltrim($getLog[1], "0");
        $invLog = DB::table('investment_logs')->find($logId);
        $investment = Investments::select('trader_id')->find($invLog->investment_id);
        $logCheck = DB::table('received_payments')->where('investment_log_id', $logId)->first();
        if($investment->trader_id == $request->trader_num && $invLog->status == 1 && $logCheck == ""){
            $proof = Storage::putFile('payments_proof', $request->file('proof'));
            $data = [
                'investment_log_id' => $invLog->id,
                'payment_proof' => $proof,
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
            ];
            DB::table('received_payments')->insert($data);
            return view('apply.payment', ['suc_msg' => 'Payment Proof has been submitted']);
        }
        return view('apply.payment', ['err_msg' => 'Invalid credentials or Expired transaction ID']);
    }

}

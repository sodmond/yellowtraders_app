<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traders;
use App\Investments;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApplicationsController extends Controller
{
    public function __construct() {
        $trader_id = "inv_".rand(1000, 9999);
        $this->trader_id = $trader_id;
    }

    private function valBankInvestment(Request $request, array $data, string $inv_type)
    {
        $this->validate($request, [
            'bank_name' => 'required|max:255',
            'account_number' => 'required|numeric|unique:bank_accounts,account_number',
            'account_name' => 'required|max:255',
            'amount' => 'required|numeric',
            'amount_words' => 'required|max:255',
            'duration' => 'required|numeric',
            'purpose' => 'nullable|max:255',
        ]);
        $bank = [
            'trader_id' => $this->trader_id,
            'bank_name' => $request->bank_name,
            'holder_name' => $request->account_name,
            'account_number' => $request->account_number,
        ];
        $monthly_pcent = DB::table('monthly_rois')->select('per_cent')->where([
            ['min', '<=', $request->amount],
            ['max', '>=', $request->amount],
        ])->get();
        $monthly_roi = ($request->amount * $monthly_pcent[0]->per_cent) / 100;
        $investment = [
            'trader_id' => $this->trader_id,
            'amount' => $request->amount,
            'amount_in_words' => $request->amount_words,
            'monthly_roi' => $monthly_roi,
            'monthly_%' => $monthly_pcent[0]->per_cent,
            'duration' => $request->duration,
            'purpose' => $request->purpose,
            'status' => '1',
        ];
        DB::beginTransaction();
        #DB::transaction(function () use ($data, $bank, $investment, $inv_type) {
        $new_bank = DB::table('bank_accounts')->insert($bank);
        $new_inv = Investments::create($investment);
        array_pop($investment);
        array_shift($investment);
        array_pop($investment);
        $investment1 = array_merge([
            'investment_id' => $new_inv->id,
            'investment_type' => $inv_type,
        ], $investment);
        $inv_log = DB::table('investment_logs')->insert($investment1);
        $trader = Traders::create($data);
            #$trader->save();
        #}, 3);
        if ($new_bank && $new_inv && $inv_log && $trader) {
            DB::commit();
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
            'email' => 'required|max:255|email|unique:Traders',
            'gender' => 'required|max:255',
            'marital' => 'required|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|numeric|unique:Traders',
            'alt_phone' => 'nullable',
            'dob' => 'required|max:255|date',
            'country' => 'required|max:255',
            'state' => 'required|max:255',
            'lga' => 'required|max:255',
            'nok_name' => 'required|max:255',
            'nok_phone' => 'required|numeric',
            'image.*' => 'required|max:255|mimes:png,jpg,jpeg',
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
        return  ($newTrader == true) ? view('apply.yellow_traders', ['suc_msg' => $suc_msg]) : view('apply.yellow_traders', ['err_msg' => $err_msg]);
    }

    public function juniorTraders()
    {
        return view('apply.junior_traders');
    }
    public function juniorTradersVal(Request $request)
    {
        $this->validate($request, [
            'fname' => 'required|max:255',
            'email' => 'required|max:255|email|unique:Traders',
            'gender' => 'required|max:255',
            'marital' => 'required|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|numeric|unique:Traders',
            'dob' => 'required|max:255|date',
            'country' => 'required|max:255',
            'state' => 'required|max:255',
            'lga' => 'required|max:255',
            'p_name' => 'required|max:255',
            'p_phone' => 'required|numeric',
            'image.*' => 'required|max:255|mimes:png,jpg,jpeg',
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
        return  ($newTrader == true) ? view('apply.junior_traders', ['suc_msg' => $suc_msg]) : view('apply.junior_traders', ['err_msg' => $err_msg]);
    }

    public function corporateTraders()
    {
        return view('apply.corporate_traders');
    }
    public function corporateTradersVal(Request $request)
    {

        $this->validate($request, [
            'cname' => 'required|max:255',
            'email' => 'required|max:255|email|unique:Traders',
            #'gender' => 'required|max:255',
            #'marital' => 'required|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|numeric|unique:Traders',
            'alt_phone' => 'nullable',
            'dob' => 'required|max:255|date',
            'country' => 'required|max:255',
            'state' => 'required|max:255',
            'city' => 'required|max:255',
            'rep_name' => 'required|max:255',
            'rep_phone' => 'required|numeric',
            'image.*' => 'required|max:255|mimes:png,jpg,jpeg',
            'ref' => 'max:255|nullable',
        ]);

        $trader_type = "3";
        $inv_type = "new";
        $image = $this->storeImage($request->file('image'));
        $data = [
            'trader_id' => $this->trader_id,
            'trader_type' => $trader_type,
            'full_name' => $request->cname,
            'marital_status' => 'none',
            'gender' => 'none',
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
            'referral' => $request->ref,
        ];
        $newTrader = $this->valBankInvestment($request, $data, $inv_type);
        $suc_msg = "Your information has been submitted";
        $err_msg = "Error submitting your information";
        #($newTrader == true) ? dd("Succesfully saved") : dd("Error saving trader");
        return  ($newTrader == true) ? view('apply.corporate_traders', ['suc_msg' => $suc_msg]) : view('apply.corporate_traders', ['err_msg' => $err_msg]);
    }

    # Topup and Rollover Form functions start here

    public function tuRoHome()
    {
        return view('apply.topup_rollover');
    }

    public function updateInv($data, $trader_id)
    {
        DB::beginTransaction();
        $sql1 = Investments::where('trader_id', $trader_id)->update($data);
        $sql2 = DB::table('investment_logs')->insert(array_merge($data, ['created_at' => date('Y-m-d H:i:s')]));
        if ($sql1 && $sql2){
            DB::commit();
            return true;
        }
        DB::rollBack();
        return false;
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
                $inv_status = Investments::where('trader_id', $trader[0]->trader_id)->get('status');
                $status = $inv_status[0]->status;
                $inv_type = "rollover";
                if ($status == 1){
                    $pend_msg = "You have a pending investment, check back in 24hrs.";
                    return view('apply.topup_rollover', ['pend_msg' => $pend_msg]);
                }
                if ($status == 2 && $days <= 10){
                    $inv_type = "topup";
                }
                $trader_arr = json_decode(json_encode($trader[0]), true);
                return view('apply.topup_rollover', array_merge($trader_arr, ['inv_type' => $inv_type]));
            }
            $err_msg = "You need to register with us before you can Topup or Rollover";
            return view('apply.topup_rollover', ['err_msg' => $err_msg]);
        }
        if ($request->turo == "rollover"){
            $this->validate($request, [
                'amount' => 'required|numeric',
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
                'monthly_%' => $monthly_pcent[0]->per_cent,
                'duration' => $request->duration,
                'purpose' => $request->purpose,
                'status' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $msg = $this->updateInv($data, $request->trader_id) ? '?msg=rollover' : '';
            return redirect('/apply/topup_rollover'.$msg);
        }
        if ($request->turo == "topup"){
            $this->validate($request, [
                'amount' => 'required|numeric',
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
                'amount' => $new_amount,
                'amount_in_words' => $request->amount_in_words,
                'monthly_roi' => $monthly_roi,
                'monthly_%' => $monthly_pcent[0]->per_cent,
                'purpose' => $request->purpose,
                'status' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $msg = $this->updateInv($data, $request->trader_id) ? '?msg=topup' : '';
            return redirect('/apply/topup_rollover'.$msg);
        }
        return redirect('/apply/topup_rollover');
    }
}

<?php

namespace App\Http\Controllers;

use App\Investments;
use App\Mail\SendMou;
use App\Traders;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use PDF;

class TraderProfileController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'nta']);
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
        $data = ['getTraderInfo' => $getTraderInfo];
        $pdfMou = PDF::loadView('admin.preview_mou', $data)
                    ->setPaper('a4')
                    ->setOptions([
                        'defaultFont' => "'Open Sans', sans-serif",
                        'tempDir' => public_path(),
                        'chroot'  => public_path(),
                    ]);
        $pdfMouName = 'mou/'.$getTraderInfo->trader_id.'.pdf';
        Storage::put($pdfMouName, $pdfMou->output());
        return view('admin.preview_mou', ['getTraderInfo' => $getTraderInfo]);
    }

    public function genPdfMou($email, $trader_id)
    {
        if ($trader_id != "" && $email != "") {
            $mouFile = 'mou/'.$trader_id.'.pdf';
            Mail::to($email)->cc('mou@yellowtraders.org')->send(new SendMou($mouFile));
            #Mail::to($email)->send(new SendMou($mouFile));
            $msg = 'MOU sent to trader successfully';
            return response()->json(['msg' => $msg]);
        }
        return response()->json(['msg' => '']);
    }

    public function editTrader($id)
    {
        $trader = Traders::find($id);
        $bank = DB::table('bank_accounts')->where('trader_id', $trader->trader_id)->first();
        return view('admin.edit_trader', ['trader' => $trader, 'bank' => $bank]);
    }

    public function updateTrader(Request $request)
    {
        $this->validate($request, [
            'fname' => 'required|max:255',
            'email' => 'required|max:255|email',
            'gender' => 'required|max:255',
            'marital' => 'required|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|numeric',
            'alt_phone' => 'nullable',
            'dob' => 'required|max:255|date',
            'country' => 'required|max:255',
            'state' => 'required|max:255',
            'lga' => 'required|max:255',
            'nok_name' => 'required|max:255',
            'nok_phone' => 'required|numeric',
            'image.*' => 'nullable|mimes:png,jpg,jpeg|size:5000',
            'ref' => 'max:255|nullable',
            'bank_name' => 'required|max:255',
            'account_number' => 'required|numeric',
            'account_name' => 'required|max:255',
        ]);
        $trader_id = $request->trader_id;
        $data = [
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
            'contact_name' => $request->nok_name,
            'contact_phone' => $request->nok_phone,
            'referral' => $request->ref,
        ];
        $traderBank = [
            'bank_name' => $request->bank_name,
            'holder_name' => $request->account_name,
            'account_number' => $request->account_number,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $traderData = $data;
        if ($request->file('image') != null){
            $image = $request->file('image');
            $imagePath = Storage::putFile('passports', $image);
            $traderData = array_merge($data, ['image' => $imagePath]);
        }
        Traders::where('trader_id', $trader_id)->update($traderData);
        DB::table('bank_accounts')->where('trader_id', $trader_id)->update($traderBank);
        return redirect()->back()->with('traderUpdateSuc', 'Trader Profile has been updated successfully');
    }

    public function deleteTrader($id)
    {
        $trader = Traders::find($id);
        return view('admin.delete_trader', ['trader' => $trader]);
    }

    public function confirmTraderdelete(Request $request)
    {
        $id = $request->id;
        $trader_id = $request->trader_id;
        $trader = Traders::find($id);
        $type = $trader->trader_type;
        Investments::where('trader_id', $trader_id)->delete();
        DB::table('bank_accounts')->where('trader_id', $trader_id)->delete();
        Traders::destroy($id);
        User::where('username', $trader_id)->delete();
        if ($type == 1) {
            return redirect('/admin/yellow_traders?msg=traderdelsuc');
        }
        if ($type == 2) {
            return redirect('/admin/junior_traders?msg=traderdelsuc');
        }
        return redirect('/admin/corporate_traders?msg=traderdelsuc');
    }

    public function archtivate($arstat, $trader_id)
    {
        /*$msg = $trader_id." - ".$arstat;
        return response()->json(['msg' => $msg]);*/
        //$ar = $arstat;
        $invmnt = Investments::select('status', 'capital')->where('trader_id', $trader_id)->first();
        if ($arstat = "arch" && $invmnt->status != 0 && $invmnt->capital == 1) {
            return response()->json(['msg' => "Error! You can't archive an active trader account."]);
        }
        if ($arstat = "arch" && $invmnt->status == 0 && $invmnt->capital == 1) {
            Investments::where('trader_id', $trader_id)->update(['capital' => 0]);
            return response()->json(['msg' => 'Trader account has been archived successfully.']);
        }
        if ($arstat = "react" && $invmnt->status == 0 && $invmnt->capital == 0) {
            Investments::where('trader_id', $trader_id)->update(['capital' => 1]);
            return response()->json(['msg' => 'Trader account has been reactivated successfully.']);
        }
        return response()->json(['msg' => 'Error! Action not completed']);
    }

    public function updateActInv(Request $request)
    {
        $this->validate($request, [
            'trader_id'     => 'required',
            'amount'        => 'required|numeric',
            'amount_words'  => 'required',
            'month_pcent'   => 'required|numeric',
            'month_roi'     => 'required|numeric',
        ]);
        $data = ['amount' => $request->amount, 'amount_in_words' => $request->amount_words,
                'monthly_roi' => $request->month_roi, 'monthly_pcent' => $request->month_pcent,
                'updated_at' => date('Y-m-d H:i:s')];
        DB::beginTransaction();
        $updtInv = Investments::where('trader_id', $request->trader_id)->update($data);
        if ($updtInv) {
            DB::commit();
            return redirect()->back()->with('message', 'Current investment updated successfully');
        }
        DB::rollBack();
        return redirect()->back()->with('message', 'Current investment updated successfully');
    }
}

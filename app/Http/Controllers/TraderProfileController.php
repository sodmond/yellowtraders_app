<?php

namespace App\Http\Controllers;

use App\Investments;
use App\Traders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TraderProfileController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
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
        return view('admin.preview_mou', ['getTraderInfo' => $getTraderInfo]);
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
        if ($type == 1) {
            return redirect('/admin/yellow_traders?msg=traderdelsuc');
        }
        if ($type == 2) {
            return redirect('/admin/junior_traders?msg=traderdelsuc');
        }
        return redirect('/admin/corporate_traders?msg=traderdelsuc');
    }
}

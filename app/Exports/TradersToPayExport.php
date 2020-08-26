<?php

namespace App\Exports;

use App\Investments;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class TradersToPayExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $tradersToPay = Investments::join('payouts', 'payouts.investment_id', '=', 'investments.id')
                    ->join('traders', 'investments.trader_id', '=', 'traders.trader_id')
                    ->join('bank_accounts', 'traders.trader_id', '=', 'bank_accounts.trader_id')
                    ->where('payouts.status', 0)
                    ->select('traders.full_name', 'investments.amount', 'payouts.roi', 'investments.monthly_pcent',
                            'bank_accounts.bank_name', DB::raw('LPAD(bank_accounts.account_number, 10, 0)'),
                            'investments.duration')
                    ->orderBy('payouts.id')
                    ->get();
        return $tradersToPay;
    }

    public function headings() : array
    {
        return [
            'NAME',
            'AMOUNT INVESTED',
            'MONTHLY ROI',
            'MONTHLY %',
            'BANK NAME',
            'ACCOUNT NUMBER',
            'DURATION'
        ];
    }
}

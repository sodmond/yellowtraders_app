<?php

namespace App\Exports;

use App\Traders;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AllTradersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($traderType) {
        $this->trader_type = $traderType;
    }

    public function collection()
    {
        $tradersToPay = Traders::join('bank_accounts', 'traders.trader_id', '=', 'bank_accounts.trader_id')
                    ->join('investments', 'traders.trader_id', '=', 'investments.trader_id')
                    ->where('traders.trader_type', $this->trader_type)
                    ->select('traders.trader_id', 'traders.full_name', 'traders.email', 'traders.phone', 'traders.other_phone',
                            'traders.gender', 'traders.address', 'traders.dob',
                            DB::raw('LPAD(bank_accounts.account_number, 10, 0)'), 'bank_accounts.bank_name',
                            'investments.amount', 'investments.monthly_roi', 'investments.monthly_pcent',
                            'investments.duration', 'investments.start_date', 'investments.end_date')
                    ->orderBy('traders.id')
                    ->get();
        return $tradersToPay;
    }

    public function headings() : array
    {
        return [
            'TRADER ID',
            'NAME',
            'EMAIL',
            'PHONE',
            'OTHER PHONE',
            'GENDER',
            'ADDRESS',
            'DATE OF BIRTH',
            'ACCOUNT NUMBER',
            'BANK NAME',
            'AMOUNT INVESTED',
            'MONTHLY ROI',
            'MONTHLY %',
            'DURATION',
            'START DATE',
            'END DATE'
        ];
    }
}

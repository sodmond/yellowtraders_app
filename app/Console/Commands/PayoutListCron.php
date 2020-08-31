<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Investments;
use App\Payouts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayoutListCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payoutlist:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Payout list to payouts table from Investments table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info("PayoutList Cron started!");

        $tradersToPay = Investments::select('id', 'monthly_roi')
                    ->whereRaw('end_date >= CURDATE()')
                    ->whereRaw('DATE_ADD(start_date, INTERVAL pay_count+1 MONTH) = CURDATE()')
                    ->where('status', 2)
                    ->get();
        foreach ($tradersToPay as $payout){
            $row = [
                'investment_id' => $payout->id,
                'roi' => $payout->monthly_roi,
            ];
            Payouts::create($row);
            Investments::where('id', $payout->id)->increment('pay_count', 1);
        }
        #$this->info('PayoutList Cron command executed successfully');
        Log::info("PayoutList Cron command migrated ".$tradersToPay->count()." records successfully");
    }
}

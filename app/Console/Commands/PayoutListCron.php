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
                    ->whereRaw('CURDATE() <= end_date')
                    ->whereRaw('DAY(CURDATE()) = DAY(start_date)')
                    ->where('status', '=', 2)
                    ->get();
        foreach ($tradersToPay as $payout){
            $row = [
                'investment_id' => $payout->id,
                'roi' => $payout->monthly_roi,
                #'created_at' => date('Y-m-d H:i:s')
            ];
            Payouts::create($row);
        }
        #$this->info('PayoutList Cron command executed successfully');
        Log::info("PayoutList Cron command executed successfully");
    }
}

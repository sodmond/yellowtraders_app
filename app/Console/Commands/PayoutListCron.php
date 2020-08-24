<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Investments;
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
        Log::info("PayoutList Cron is working fine!");

        $tradersToPay = Investments::select('id', 'monthly_roi')
                    ->where(DB::raw('CURDATE()'), '<=', 'end_date')
                    ->where(DB::raw('DAY(CURDATE())'), '=', DB::raw('DAY(start_date)'))
                    ->where('status', '=', 2)
                    ->get();
        foreach ($tradersToPay as $payout){
            DB::table('payouts')->insert([
                'investment_id' => $payout->id,
                'roi' => $payout->monthly_roi,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        Investments::where('end_date', '<', DB::raw('CURDATE()'))->update(['status' => 0]);

        $this->info('PayoutList Cron command executed successfully');
    }
}

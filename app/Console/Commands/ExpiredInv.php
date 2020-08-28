<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Investments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpiredInv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiredInv:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Expired Investments as inactive';

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
        $updateInv = Investments::where('end_date', '<', DB::raw('CURDATE()'))->update(['status' => 0]);
        if ($updateInv) {
            Log::info('Exipred investments has been set to inactive!');
        }
    }
}

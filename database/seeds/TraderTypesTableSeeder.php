<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TraderTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('trader_types')->insert([
            [
                'name' => 'yellow',
                'min_investment' => '150000',
                'min_topup' => '150000',
                'durations' => '3,6',
            ],[
                'name' => 'junior',
                'min_investment' => '50000',
                'min_topup' => '50000',
                'durations' => '3,6',
            ],[
                'name' => 'corporate',
                'min_investment' => '2000000',
                'min_topup' => '1000000',
                'durations' => '6,9,12',
            ]
        ]);
    }
}

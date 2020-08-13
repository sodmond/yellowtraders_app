<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonthlyROIsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('monthly_rois')->insert([
            ['min' => '150000', 'max' => '5000000', 'per_cent' => '20'],
            ['min' => '5000001', 'max' => '10000000', 'per_cent' => '15'],
            ['min' => '10000001', 'max' => '15000000', 'per_cent' => '10'],
            ['min' => '15000001', 'max' => '20000000', 'per_cent' => '5'],
            ['min' => '20000001', 'max' => '25000000', 'per_cent' => '2.5'],
            ['min' => '25000001', 'max' => '30000000', 'per_cent' => '1'],
            ['min' => '30000001', 'max' => '', 'per_cent' => '0']
        ]);
    }
}

<?php

use App\Branch;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BranchesTableSeeder extends Seeder
{
    public function run() {
        Branch::insert([
            [ 
                'branch_title' => 'Tashan Pharmacy Store 001',
                'branch_code' => '001',
                'user_id' => 5,
                'branch_phone' => '013456789014',
                'branch_address' => 'Shop no: 16, 1st Floor, Lola Shopping Mall, Road 6, Mirpur 11, Dhaka',
                'branch_note' => 'Profitable',
                'branch_start_date' => Carbon::parse(now())->format('Y-m-d'),
                'branch_status' => 1,
                'created_at' => now(),
            ],
            [ 
                'branch_title' => 'Tashan Pharmacy Store 002',
                'branch_code' => '002',
                'user_id' => 6,
                'branch_phone' => '013456789015',
                'branch_address' => 'Shop no: 56, 1st Floor, Monsur Building, Road 4, Mirpur 12, Dhaka',
                'branch_note' => 'Profitable',
                'branch_start_date' => Carbon::parse(now())->format('Y-m-d'),
                'branch_status' => 1,
                'created_at' => now(),
            ],
        ]);
    }
}

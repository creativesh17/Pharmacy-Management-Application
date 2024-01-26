<?php

use App\Refund;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RefundsTableSeeder extends Seeder
{
    public function run() {
        Refund::insert([
            [ 
                'invoice_id' => 9,
                'user_id' => 5,
                'branch_id' => 1,
                'customer_id' => 5,
                'refund_date' => Carbon::parse(now())->format('Y-m-d'),
                'payment_type' => 'Cash',
                'refund_total' => 175.00,
                'refund_cut' => 0.00,
                'refund_nettotal' => 175.00,
                'refund_paid' => 175.00,
                'refund_note' => 'good!',
                'created_at' => now(),
            ],
            [ 
                'invoice_id' => 4,
                'user_id' => 5,
                'branch_id' => 1,
                'customer_id' => 2,
                'refund_date' => Carbon::parse(now())->format('Y-m-d'),
                'payment_type' => 'Cash',
                'refund_total' => 135.00,
                'refund_cut' => 20.00,
                'refund_nettotal' => 115.00,
                'refund_paid' => 115.00,
                'refund_note' => 'good!',
                'created_at' => now(),
            ],
            [ 
                'invoice_id' => 11,
                'user_id' => 6,
                'branch_id' => 2,
                'customer_id' => 1,
                'refund_date' => Carbon::parse(now())->format('Y-m-d'),
                'payment_type' => 'Cash',
                'refund_total' => 135.00,
                'refund_cut' => 0.00,
                'refund_nettotal' => 135.00,
                'refund_paid' => 135.00,
                'refund_note' => 'good!',
                'created_at' => now(),
            ],
            [ 
                'invoice_id' => 12,
                'user_id' => 6,
                'branch_id' => 2,
                'customer_id' => 1,
                'refund_date' => Carbon::parse(now())->format('Y-m-d'),
                'payment_type' => 'Cash',
                'refund_total' => 125.00,
                'refund_cut' => 0.00,
                'refund_nettotal' => 125.00,
                'refund_paid' => 125.00,
                'refund_note' => 'good!',
                'created_at' => now(),
            ],
        ]);
    }
}

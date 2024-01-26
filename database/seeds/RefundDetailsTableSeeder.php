<?php

use App\RefundDetails;
use Illuminate\Database\Seeder;

class RefundDetailsTableSeeder extends Seeder
{
    public function run() {
        RefundDetails::insert([
            [ 
                'refund_id' => 1,
                'medicine_id' => 1,
                'medicine_name' => 'Fenadin 180',
                'sold_qty' => 20,
                'refund_qty' => 10,
                'sell_price' => 10.00,
                'total_price' => 100.00,
                'created_at' => now(),
            ],
            [ 
                'refund_id' => 1,
                'medicine_id' => 2,
                'medicine_name' => 'Duofix 500',
                'sold_qty' => 15,
                'refund_qty' => 5,
                'sell_price' => 15.00,
                'total_price' => 75.00,
                'created_at' => now(),
            ],
            [ 
                'refund_id' => 2,
                'medicine_id' => 10,
                'medicine_name' => 'Trevox 500',
                'sold_qty' => 10,
                'refund_qty' => 5,
                'sell_price' => 15.00,
                'total_price' => 75.00,
                'created_at' => now(),
            ],
            [ 
                'refund_id' => 2,
                'medicine_id' => 5,
                'medicine_name' => 'Zif-CI',
                'sold_qty' => 10,
                'refund_qty' => 5,
                'sell_price' => 12.00,
                'total_price' => 60.00,
                'created_at' => now(),
            ],
            [ 
                'refund_id' => 3,
                'medicine_id' => 7,
                'medicine_name' => 'Napa 500',
                'sold_qty' => 20,
                'refund_qty' => 5,
                'sell_price' => 2.00,
                'total_price' => 10.00,
                'created_at' => now(),
            ],
            [ 
                'refund_id' => 3,
                'medicine_id' => 6,
                'medicine_name' => 'Doxicap 100',
                'sold_qty' => 20,
                'refund_qty' => 5,
                'sell_price' => 10.00,
                'total_price' => 50.00,
                'created_at' => now(),
            ],
            [ 
                'refund_id' => 3,
                'medicine_id' => 2,
                'medicine_name' => 'Duofix 500',
                'sold_qty' => 30,
                'refund_qty' => 5,
                'sell_price' => 15.00,
                'total_price' => 75.00,
                'created_at' => now(),
            ],
            [ 
                'refund_id' => 4,
                'medicine_id' => 10,
                'medicine_name' => 'Trevox 500',
                'sold_qty' => 20,
                'refund_qty' => 5,
                'sell_price' => 15.00,
                'total_price' => 75.00,
                'created_at' => now(),
            ],
            [ 
                'refund_id' => 4,
                'medicine_id' => 1,
                'medicine_name' => 'Fenadin 180',
                'sold_qty' => 20,
                'refund_qty' => 5,
                'sell_price' => 10.00,
                'total_price' => 50.00,
                'created_at' => now(),
            ],
        ]);
    }
}

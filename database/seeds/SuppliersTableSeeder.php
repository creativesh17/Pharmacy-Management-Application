<?php

use App\Supplier;
use Illuminate\Database\Seeder;

class SuppliersTableSeeder extends Seeder
{
    public function run() {
        Supplier::insert([
            [ 
                'sup_name' => 'Sohel Khan',
                'sup_phone' => '01991234561',
                'sup_email' => 'sohel@voila.com',
                'sup_address' => 'Shyamoli',
                'sup_note' => 'Good!',
                'sup_status' => 1,
                'created_at' => now(),
            ],
            [ 
                'sup_name' => 'Rasel Khan',
                'sup_phone' => '02991234562',
                'sup_email' => 'rasel@voila.com',
                'sup_address' => 'Mirpur 10',
                'sup_note' => 'Good!',
                'sup_status' => 1,
                'created_at' => now(),
            ],
            [ 
                'sup_name' => 'Qasel Khan',
                'sup_phone' => '03991234563',
                'sup_email' => 'qasel@voila.com',
                'sup_address' => 'Karwanbazar',
                'sup_note' => 'Good!',
                'sup_status' => 1,
                'created_at' => now(),
            ],
            [ 
                'sup_name' => 'Tasel Khan',
                'sup_phone' => '04991234564',
                'sup_email' => 'tasel@voila.com',
                'sup_address' => 'Mirpur 14',
                'sup_note' => 'Good!',
                'sup_status' => 1,
                'created_at' => now(),
            ],
            [ 
                'sup_name' => 'Yasel Khan',
                'sup_phone' => '05991234565',
                'sup_email' => 'yasel@voila.com',
                'sup_address' => 'Dhanmondi',
                'sup_note' => 'Good!',
                'sup_status' => 1,
                'created_at' => now(),
            ],
            [ 
                'sup_name' => 'Hasel Khan',
                'sup_phone' => '06991234566',
                'sup_email' => 'hasel@voila.com',
                'sup_address' => 'Ibrahimpur',
                'sup_note' => 'Good!',
                'sup_status' => 1,
                'created_at' => now(),
            ],
        ]);
    }
}

<?php

use App\PharmacySetting;
use Illuminate\Database\Seeder;

class PharmacySettingsTableSeeder extends Seeder
{

    public function run() {
        PharmacySetting::insert([
            [ 
                'ph_name' => 'Tashan Pharmacy Store',
                'ph_phone' => '017542435353',
                'ph_email' => 'info@khanpharmacyvoila.com',
                'ph_address' => '8/A, Central Road, Dhanmondi, Dhaka',
                'ph_about' => 'Established in 2000',
                'ph_logo' => 'ph_logo_1590705379.jpg',
                'created_at' => now(),
            ],
        ]);
    }
}

<?php

use App\Manufacturer;
use Illuminate\Database\Seeder;

class ManufacturersTableSeeder extends Seeder
{
    public function run() {
        Manufacturer::insert([
            [ 
                'manu_name' => 'Default-Manufacturer',
                'manu_note' => 'Irregular',
                'created_at' => now(),
            ],
            [ 
                'manu_name' => 'Square Pharmaceuticals Limited',
                'manu_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'manu_name' => 'Beximco Pharmaceuticals Limited',
                'manu_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'manu_name' => 'ACL Pharmaceuticals Limited',
                'manu_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'manu_name' => 'Renata Pharmaceuticals Limited',
                'manu_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'manu_name' => 'Ad-dins Pharmaceuticals Limited',
                'manu_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'manu_name' => 'Incepta Pharmaceuticals Limited',
                'manu_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'manu_name' => 'Acme Pharmaceuticals Limited',
                'manu_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'manu_name' => 'Opsonin Pharmaceutical Limited',
                'manu_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'manu_name' => 'Ziska Pharmaceutical Limited',
                'manu_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'manu_name' => 'Popular Pharmaceutical Limited',
                'manu_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'manu_name' => 'Globe Pharmaceutical Limited',
                'manu_note' => 'Regular',
                'created_at' => now(),
            ],
        ]);
    }
}

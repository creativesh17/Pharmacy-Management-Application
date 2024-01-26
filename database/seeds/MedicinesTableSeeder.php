<?php

use App\Medicine;
use Illuminate\Database\Seeder;

class MedicinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Medicine::insert([
            [ 
                'med_name' => 'Fenadin 180',
                'generic_name' => 'Fexofenadin HCL USP',
                'medicinecategory_id' => 2,
                'manufacturer_id' => 5,
                'sell_price' => 10.00,
                'note' => 'Cold',
                'created_at' => now(),
            ],
            [ 
                'med_name' => 'Duofix 500',
                'generic_name' => 'Naproxen BP 500mg + Esomeprazole 20mg',
                'medicinecategory_id' => 2,
                'manufacturer_id' => 9,
                'sell_price' => 15.00,
                'note' => 'Hard Cold',
                'created_at' => now(),
            ],
            [ 
                'med_name' => 'Rhinozol 15ml',
                'generic_name' => 'Xylometazoline HCL',
                'medicinecategory_id' => 6,
                'manufacturer_id' => 8,
                'sell_price' => 25.00,
                'note' => 'nasal drop',
                'created_at' => now(),
            ],
            [ 
                'med_name' => 'Ranid 150mg',
                'generic_name' => 'Ranitidine USP 150mg',
                'medicinecategory_id' => 2,
                'manufacturer_id' => 10,
                'sell_price' => 5.00,
                'note' => 'running medicine',
                'created_at' => now(),
            ],
            [ 
                'med_name' => 'Zif-CI',
                'generic_name' => 'Carbonyl Iron 50mg + Folic Acid 500mcg + Zinc Sulphate Monohydrate 22.5mg',
                'medicinecategory_id' => 3,
                'manufacturer_id' => 2,
                'sell_price' => 12.00,
                'note' => 'kind of vitamin',
                'created_at' => now(),
            ],
            [ 
                'med_name' => 'Doxicap 100',
                'generic_name' => 'Doxycycline Hyclate USP 100mg',
                'medicinecategory_id' => 3,
                'manufacturer_id' => 10,
                'sell_price' => 10.00,
                'note' => 'throat pain',
                'created_at' => now(),
            ],
            [ 
                'med_name' => 'Napa 500',
                'generic_name' => 'Paracetamol 500mg',
                'medicinecategory_id' => 2,
                'manufacturer_id' => 3,
                'sell_price' => 2.00,
                'note' => 'fever',
                'created_at' => now(),
            ],
            [ 
                'med_name' => 'Sitagil M 50/1000ER',
                'generic_name' => 'Sitagliptin 50mg + Metaformin HCL 1000mg',
                'medicinecategory_id' => 2,
                'manufacturer_id' => 7,
                'sell_price' => 10.00,
                'note' => 'gastric',
                'created_at' => now(),
            ],
            [ 
                'med_name' => 'Montair 10mg',
                'generic_name' => 'Montelukust  10mg',
                'medicinecategory_id' => 2,
                'manufacturer_id' => 7,
                'sell_price' => 20.00,
                'note' => 'cold allergy',
                'created_at' => now(),
            ],
            [ 
                'med_name' => 'Trevox 500',
                'generic_name' => 'Levofloxacin 500',
                'medicinecategory_id' => 2,
                'manufacturer_id' => 2,
                'sell_price' => 15.00,
                'note' => 'cold antibiotic',
                'created_at' => now(),
            ],
            [ 
                'med_name' => 'Pol Plus BP 500mg',
                'generic_name' => 'Paracetamol BP 500mg + Caffeine BP 65mg',
                'medicinecategory_id' => 2,
                'manufacturer_id' => 12,
                'sell_price' => 3.00,
                'note' => 'cold antibiotic',
                'created_at' => now(),
            ],
        ]);
    }
}

// 321-50-60 trevox
// 083-255-006 Pol Plus
// 025-662-50 Precodil 10
<?php

use App\MedicineCategory;
use Illuminate\Database\Seeder;

class MedicineCategoryTableSeeder extends Seeder
{
    public function run() {
        MedicineCategory::insert([
            [ 
                'cate_name' => 'Default-Category',
                'cate_note' => 'Irregular',
                'created_at' => now(),
            ],
            [ 
                'cate_name' => 'Tablet',
                'cate_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'cate_name' => 'Capsule',
                'cate_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'cate_name' => 'Syrup',
                'cate_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'cate_name' => 'Lotion',
                'cate_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'cate_name' => 'Drop',
                'cate_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'cate_name' => 'Injection',
                'cate_note' => 'Regular',
                'created_at' => now(),
            ],
            [ 
                'cate_name' => 'Cream & Ointment',
                'cate_note' => 'Regular',
                'created_at' => now(),
            ],
        ]);
    }
}

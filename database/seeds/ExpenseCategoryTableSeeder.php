<?php

use App\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        ExpenseCategory::insert([
            [
                'expcate_name' => 'Salary',
                'expcate_remarks' => 'regular',
                'user_id' => 1,
                'expcate_slug' => 'salary',
                'created_at' => now(),
            ],
            [
                'expcate_name' => 'Snacks',
                'expcate_remarks' => 'regular',
                'user_id' => 1,
                'expcate_slug' => 'snacks',
                'created_at' => now(),
            ],
            [
                'expcate_name' => 'Rent',
                'expcate_remarks' => 'regular',
                'user_id' => 1,
                'expcate_slug' => 'rent',
                'created_at' => now(),
            ],
            [
                'expcate_name' => 'Utilities',
                'expcate_remarks' => 'regular',
                'user_id' => 1,
                'expcate_slug' => 'utilities',
                'created_at' => now(),
            ],
            [
                'expcate_name' => 'Allowances',
                'expcate_remarks' => 'regular',
                'user_id' => 1,
                'expcate_slug' => 'allowances',
                'created_at' => now(),
            ],
            [
                'expcate_name' => 'Bonus',
                'expcate_remarks' => 'regular',
                'user_id' => 1,
                'expcate_slug' => 'bonus',
                'created_at' => now(),
            ],
            [
                'expcate_name' => 'Others',
                'expcate_remarks' => 'unknown',
                'user_id' => 1,
                'expcate_slug' => 'others',
                'created_at' => now(),
            ],
        ]);
    }
}

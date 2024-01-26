<?php

use App\Expense;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExpensesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Expense::insert([
            [
                'branch_id' => 1,
                'user_id' => 5,
                'expensecategory_id' => 1,
                'expense_details' => "Staff salaries of the month",
                'expense_amount' => 30000,
                'expense_date' => Carbon::parse(now())->format('Y-m-d'),
                'created_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'branch_id' => 2,
                'user_id' => 6,
                'expensecategory_id' => 1,
                'expense_details' => 'Staff salaries of the month',
                'expense_amount' => 30000,
                'expense_date' => Carbon::parse(now())->format('Y-m-d'),
                'created_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'branch_id' => 1,
                'user_id' => 5,
                'expensecategory_id' => 2,
                'expense_details' => 'Snacks for evening',
                'expense_amount' => 100,
                'expense_date' => Carbon::parse(now())->format('Y-m-d'),
                'created_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'branch_id' => 2,
                'user_id' => 6,
                'expensecategory_id' => 2,
                'expense_details' => 'Snacks for evening',
                'expense_amount' => 100,
                'expense_date' => Carbon::parse(now())->format('Y-m-d'),
                'created_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'branch_id' => 1,
                'user_id' => 5,
                'expensecategory_id' => 3,
                'expense_details' => 'Rent of the month',
                'expense_amount' => 15000,
                'expense_date' => Carbon::parse(now())->format('Y-m-d'),
                'created_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'branch_id' => 2,
                'user_id' => 6,
                'expensecategory_id' => 3,
                'expense_details' => 'Rent of the month',
                'expense_amount' => 15000,
                'expense_date' => Carbon::parse(now())->format('Y-m-d'),
                'created_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'branch_id' => 1,
                'user_id' => 5,
                'expensecategory_id' => 4,
                'expense_details' => 'Utilities(Bills) of the month',
                'expense_amount' => 2000,
                'expense_date' => Carbon::parse(now())->format('Y-m-d'),
                'created_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'branch_id' => 2,
                'user_id' => 6,
                'expensecategory_id' => 4,
                'expense_details' => 'Utilities(Bills) of the month',
                'expense_amount' => 2000,
                'expense_date' => Carbon::parse(now())->format('Y-m-d'),
                'created_at' => Carbon::now()->toDateTimeString(),
            ],
        ]);
    }
}

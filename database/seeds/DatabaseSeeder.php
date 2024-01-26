<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(PharmacySettingsTableSeeder::class);
        $this->call(WebSettingsTableSeeder::class);
        $this->call(BranchesTableSeeder::class);
        $this->call(SuppliersTableSeeder::class);
        $this->call(MedicineCategoryTableSeeder::class);
        $this->call(ManufacturersTableSeeder::class);
        $this->call(MedicinesTableSeeder::class);
        $this->call(PurchasesTableSeeder::class);
        $this->call(PurchaseDetailsTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(InvoicesTableSeeder::class);
        $this->call(InvoiceDetailsTableSeeder::class);
        $this->call(RefundsTableSeeder::class);
        $this->call(RefundDetailsTableSeeder::class);
        $this->call(StaffsTableSeeder::class);
        $this->call(ExpenseCategoryTableSeeder::class);
        $this->call(ExpensesTableSeeder::class);
    }
}

<?php

use App\Customer;
use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    public function run() {
        Customer::insert([
            [
                'customer_name' => 'Walking Customer',
                'user_id' => 1,
                'customer_phone' => 'null',
                'customer_email' => 'null@null.com',
                'customer_address' => 'null',
                'created_at' => now(),
            ],
            [
                'customer_name' => 'Wafi Ali',
                'user_id' => 5,
                'customer_phone' => '01987654321',
                'customer_email' => 'wafi@voila.com',
                'customer_address' => 'Mirpur 11',
                'created_at' => now(),
            ],
            [
                'customer_name' => 'Shafi Ali',
                'user_id' => 5,
                'customer_phone' => '01877654321',
                'customer_email' => 'shafi@voila.com',
                'customer_address' => 'Mirpur 11',
                'created_at' => now(),
            ],
            [
                'customer_name' => 'Rafi Ali',
                'user_id' => 5,
                'customer_phone' => '01767654321',
                'customer_email' => 'rafi@voila.com',
                'customer_address' => 'Mirpur 11',
                'created_at' => now(),
            ],
            [
                'customer_name' => 'Mafi Ali',
                'user_id' => 5,
                'customer_phone' => '01657654321',
                'customer_email' => 'mafi@voila.com',
                'customer_address' => 'Mirpur 11',
                'created_at' => now(),
            ],
            [
                'customer_name' => 'Qafi Ali',
                'user_id' => 6,
                'customer_phone' => '01547654321',
                'customer_email' => 'qafi@voila.com',
                'customer_address' => 'Mirpur 12',
                'created_at' => now(),
            ],
            [
                'customer_name' => 'Afi Ali',
                'user_id' => 6,
                'customer_phone' => '01437654321',
                'customer_email' => 'afi@voila.com',
                'customer_address' => 'Mirpur 12',
                'created_at' => now(),
            ],
            [
                'customer_name' => 'Nafi Ali',
                'user_id' => 6,
                'customer_phone' => '01327654321',
                'customer_email' => 'nafi@voila.com',
                'customer_address' => 'Mirpur 12',
                'created_at' => now(),
            ],
            [
                'customer_name' => 'Lafi Ali',
                'user_id' => 6,
                'customer_phone' => '01217654321',
                'customer_email' => 'lafi@voila.com',
                'customer_address' => 'Mirpur 12',
                'created_at' => now(),
            ],
        ]);
    }
}

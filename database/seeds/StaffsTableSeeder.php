<?php

use App\Staff;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class StaffsTableSeeder extends Seeder
{
    public function run() {
        Schema::disableForeignKeyConstraints();
        Staff::truncate();
        
        Staff::insert([
            [
                'name' => 'Shubab Mirja',
                'joining_date' => Carbon::parse(now())->format('Y-m-d'),
                'phone' => '0180192837465',
                'email' => 'shubab@voila.com',
                'nid' => '1999432156789500',
                'branch_id' => 1,
                'photo' => '',
                'current_address' => "House 4/A, Ahshan Road, Mirpur 11",
                'permanent_address' => "Jhenaidah",
                'note' => "Good!",
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Rubab Shirja',
                'joining_date' => Carbon::parse(now())->format('Y-m-d'),
                'phone' => '0171029837456',
                'email' => 'rubab@voila.com',
                'nid' => '199734115566798400',
                'branch_id' => 1,
                'photo' => '',
                'current_address' => "House 7/A, Robin Road, Mirpur 11",
                'permanent_address' => "Mymensingh",
                'note' => "Good!",
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Lubab Yajra',
                'joining_date' => Carbon::parse(now())->format('Y-m-d'),
                'phone' => '0160282837445',
                'email' => 'lubab@voila.com',
                'nid' => '199624228866798200',
                'branch_id' => 1,
                'photo' => '',
                'current_address' => "House 2/A, Shakil Road, Mirpur 11",
                'permanent_address' => "Jessore",
                'note' => "Good!",
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Shadab Khalid',
                'joining_date' => Carbon::parse(now())->format('Y-m-d'),
                'phone' => '0150392416466',
                'email' => 'shadab@voila.com',
                'nid' => '199572473776798241',
                'branch_id' => 2,
                'photo' => '',
                'current_address' => "House 9/A, Ali Road, Mirpur 12",
                'permanent_address' => "Khulna",
                'note' => "Good!",
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Wadab Walid',
                'joining_date' => Carbon::parse(now())->format('Y-m-d'),
                'phone' => '0140492413309',
                'email' => 'wadab@voila.com',
                'nid' => '199462523292027242',
                'branch_id' => 2,
                'photo' => '',
                'current_address' => "House 1/A, Gatab Road, Mirpur 12",
                'permanent_address' => "Dhaka",
                'note' => "Good!",
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Afdab Jalid',
                'joining_date' => Carbon::parse(now())->format('Y-m-d'),
                'phone' => '0130792412204',
                'email' => 'afbab@voila.com',
                'nid' => '199352523292037352',
                'branch_id' => 2,
                'photo' => '',
                'current_address' => "House 3/A, Laat Road, Mirpur 12",
                'permanent_address' => "Chattogram",
                'note' => "Good!",
                'status' => 1,
                'created_at' => now(),
            ],
        ]);

        Schema::enableForeignKeyConstraints();
    }
}

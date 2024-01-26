<?php

use App\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Schema::disableForeignKeyConstraints();
        Role::truncate();

        Role::insert([
            [ 
                'role_name' => 'Superadmin',
                'created_at' => now(),
            ],
            [ 
                'role_name' => 'Admin',
                'created_at' => now(),
            ],
            [ 
                'role_name' => 'Dispenser',
                'created_at' => now(),
            ],
        ]);

        Schema::enableForeignKeyConstraints();
    }
}

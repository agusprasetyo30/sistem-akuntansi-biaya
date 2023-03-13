<?php

namespace Database\Seeders;

use App\Models\Management_Role;
use Illuminate\Database\Seeder;

class ManagementRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Management_Role::create([
            'role_id' => 1,
            'company_code' => 'B000',
        ]);

        Management_Role::create([
            'role_id' => 1,
            'company_code' => 'B000',
        ]);

        Management_Role::create([
            'role_id' => 2,
            'company_code' => 'B000',
        ]);

        Management_Role::create([
            'role_id' => 3,
            'company_code' => 'B000',
        ]);

        Management_Role::create([
            'role_id' => 4,
            'company_code' => 'B000',
        ]);

        Management_Role::create([
            'role_id' => 5,
            'company_code' => 'B000',
        ]);

        Management_Role::create([
            'role_id' => 1,
            'company_code' => 'B000',
        ]);
    }
}

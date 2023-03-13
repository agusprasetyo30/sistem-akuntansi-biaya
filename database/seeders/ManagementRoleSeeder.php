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
            'user_id' => 1,
            'role_id' => 1,
            'username' => 'PilotJinix',
            'company_code' => 'B000',
        ]);

        Management_Role::create([
            'user_id' => 2,
            'role_id' => 1,
            'username' => 'Super_Admin',
            'company_code' => 'B000',
        ]);

        Management_Role::create([
            'user_id' => 3,
            'role_id' => 2,
            'username' => 'Admin_Company',
            'company_code' => 'B000',
        ]);

        Management_Role::create([
            'user_id' => 4,
            'role_id' => 3,
            'username' => 'Approval',
            'company_code' => 'B000',
        ]);

        Management_Role::create([
            'user_id' => 5,
            'role_id' => 4,
            'username' => 'Reviewer',
            'company_code' => 'B000',
        ]);

        Management_Role::create([
            'user_id' => 6,
            'role_id' => 5,
            'username' => 'Bpo',
            'company_code' => 'B000',
        ]);

        Management_Role::create([
            'user_id' => 7,
            'role_id' => 1,
            'username' => 'bayu',
            'company_code' => 'B000',
        ]);
    }
}
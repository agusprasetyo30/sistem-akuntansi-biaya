<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'nama_role' => 'Super_Admin',
            'is_active' => true,
            'company_code'=> 'B001',
        ]);

        Role::create([
            'nama_role' => 'Admin_Company',
            'is_active' => true,
            'company_code'=> 'B001',
        ]);

        Role::create([
            'nama_role' => 'Approval',
            'is_active' => true,
            'company_code'=> 'B001',
        ]);

        Role::create([
            'nama_role' => 'Reviewer',
            'is_active' => true,
            'company_code'=> 'B001',
        ]);

        Role::create([
            'nama_role' => 'Bpo',
            'is_active' => true,
            'company_code'=> 'B001',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\ManagementUserRole;
use Illuminate\Database\Seeder;

class ManagementUserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ManagementUserRole::create([
            'user_id' => 1,
            'role_id' => 1,
        ]);
        
        ManagementUserRole::create([
            'user_id' => 2,
            'role_id' => 1,
        ]);
        
        ManagementUserRole::create([
            'user_id' => 3,
            'role_id' => 2,
        ]);
        
        ManagementUserRole::create([
            'user_id' => 4,
            'role_id' => 3,
        ]);
        
        ManagementUserRole::create([
            'user_id' => 5,
            'role_id' => 4,
        ]);
        
        ManagementUserRole::create([
            'user_id' => 6,
            'role_id' => 5,
        ]);
        
        ManagementUserRole::create([
            'user_id' => 7,
            'role_id' => 1,
        ]);
    }
}

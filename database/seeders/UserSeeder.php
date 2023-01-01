<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Agung Santoso',
            'username' => 'PilotJinix',
            'password'=> bcrypt('Petrokimia1'),
            'company_code'=> 'B000',
        ]);

        User::create([
            'name' => 'Super_Admin',
            'username' => 'Super_Admin',
            'password'=> bcrypt('Petrokimia1'),
            'company_code'=> 'B000',
        ]);

        User::create([
            'name' => 'Admin_Company',
            'username' => 'Admin_Company',
            'password'=> bcrypt('Petrokimia1'),
            'company_code'=> 'B000',
        ]);

        User::create([
            'name' => 'Approval',
            'username' => 'Approval',
            'password'=> bcrypt('Petrokimia1'),
            'company_code'=> 'B000',
        ]);

        User::create([
            'name' => 'Reviewer',
            'username' => 'Reviewer',
            'password'=> bcrypt('Petrokimia1'),
            'company_code'=> 'B000',
        ]);

        User::create([
            'name' => 'Bpo',
            'username' => 'Bpo',
            'password'=> bcrypt('Petrokimia1'),
            'company_code'=> 'B000',
        ]);

        User::create([
            'name' => 'bayu',
            'username' => 'bayu',
            'password'=> bcrypt('Petrokimia1'),
            'company_code'=> 'B000',
        ]);
    }
}

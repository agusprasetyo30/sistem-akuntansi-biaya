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
            'email' => 'PilotJinix@gmail.com',
            'password'=> bcrypt('Petrokimia1'),
        ]);

        User::create([
            'name' => 'Super_Admin',
            'username' => 'Super_Admin',
            'email' => 'Super_Admin@gmail.com',
            'password'=> bcrypt('Petrokimia1'),
        ]);

        User::create([
            'name' => 'Admin_Company',
            'username' => 'Admin_Company',
            'email' => 'Admin_Company@gmail.com',
            'password'=> bcrypt('Petrokimia1'),
        ]);

        User::create([
            'name' => 'Approval',
            'username' => 'Approval',
            'email' => 'Approval@gmail.com',
            'password'=> bcrypt('Petrokimia1'),
        ]);

        User::create([
            'name' => 'Reviewer',
            'username' => 'Reviewer',
            'email' => 'Reviewer@gmail.com',
            'password'=> bcrypt('Petrokimia1'),
        ]);

        User::create([
            'name' => 'Bpo',
            'username' => 'Bpo',
            'email' => 'Bpo@gmail.com',
            'password'=> bcrypt('Petrokimia1'),
        ]);
    }
}

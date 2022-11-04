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
    }
}

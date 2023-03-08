<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset cahced roles and permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create posts']);
        Permission::create(['name' => 'read posts']);
        Permission::create(['name' => 'update posts']);
        Permission::create(['name' => 'delete posts']);
        Permission::create(['name' => 'submit posts']);
        Permission::create(['name' => 'approve posts']);

        //create roles and assign existing permissions
        $writerRole = Role::create(['name' => 'writer']);
        $writerRole->givePermissionTo('create posts');
        $writerRole->givePermissionTo('read posts');
        $writerRole->givePermissionTo('update posts');
        $writerRole->givePermissionTo('delete posts');

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo('create posts');
        $adminRole->givePermissionTo('read posts');
        $adminRole->givePermissionTo('update posts');
        $adminRole->givePermissionTo('delete posts');
        $adminRole->givePermissionTo('submit posts');
        $adminRole->givePermissionTo('approve posts');

        $superadminRole = Role::create(['name' => 'super-admin']);
        // gets all permissions via Gate::before rule

        // create demo users
        $user = User::create([
            'name' => 'Example user',
            'username' => 'writer@mail.com',
            'password' => bcrypt('12345678'),
            'company_code' => 'B000',
        ]);
        $user->assignRole($writerRole);

        $user = User::create([
            'name' => 'Example admin user',
            'username' => 'admin@mail.com',
            'password' => bcrypt('12345678'),
            'company_code' => 'B000',
        ]);
        $user->assignRole($adminRole);

        $user = User::create([
            'name' => 'Example superadmin user',
            'username' => 'superadmin@mail.com',
            'password' => bcrypt('12345678'),
            'company_code' => 'B000',
        ]);
        $user->assignRole($superadminRole);

        $user = User::create([
            'name' => 'Agung Santoso',
            'username' => 'PilotJinix',
            'password' => bcrypt('Petrokimia1'),
            'company_code' => 'B000',
        ]);
        $user->assignRole($superadminRole);

        $user = User::create([
            'name' => 'Bayu Luky',
            'username' => 'lukybayu',
            'password' => bcrypt('Petrokimia1'),
            'company_code' => 'B000',
        ]);
        $user->assignRole($superadminRole);
    }
}

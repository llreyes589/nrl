<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'create user']);

        Permission::create(['name' => 'edit facility']);
        Permission::create(['name' => 'delete facility']);
        Permission::create(['name' => 'create facility']);

        Permission::create(['name' => 'add certificate']);
        Permission::create(['name' => 'prepare']);
        Permission::create(['name' => 'verify']);
        Permission::create(['name' => 'approve']);
        Permission::create(['name' => 'issue certificate']);

        $superadmin = Role::create(['name' => 'Super-Admin']);
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo('edit user');
        $admin->givePermissionTo('delete user');
        $admin->givePermissionTo('create user');
        $admin->givePermissionTo('edit facility');
        $admin->givePermissionTo('delete facility');
        $admin->givePermissionTo('create facility');

        $encoder = Role::create(['name' => 'encoder']);
        $encoder->givePermissionTo('add certificate');
        $encoder->givePermissionTo('prepare');
        
        $verifier = Role::create(['name' => 'verifier']);
        $verifier->givePermissionTo('verify');
        
        $head = Role::create(['name' => 'head']);
        $head->givePermissionTo('approve');
        $head->givePermissionTo('issue certificate');

        $admin_user = \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123'),
        ]);

        $encoder_user = \App\Models\User::create([
            'name' => 'Encoder',
            'email' => 'encoder@gmail.com',
            'password' => bcrypt('123'),
        ]);

        $verifier_user = \App\Models\User::create([
            'name' => 'Verifier',
            'email' => 'verifier@gmail.com',
            'password' => bcrypt('123'),
        ]);

        $head_user = \App\Models\User::create([
            'name' => 'Head',
            'email' => 'head@gmail.com',
            'password' => bcrypt('123'),
        ]);

        $admin_user->assignRole($superadmin);
        $encoder_user->assignRole($encoder);
        $verifier_user->assignRole($verifier);
        $head_user->assignRole($head);


    }
}

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
        Permission::create(['name' => 'verify']);
        Permission::create(['name' => 'endorse']);
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
        $encoder->givePermissionTo('verify');
        
        $supervisor = Role::create(['name' => 'supervisor']);
        $supervisor->givePermissionTo('endorse');
        
        $head = Role::create(['name' => 'head']);
        $head->givePermissionTo('approve');
        $head->givePermissionTo('issue certificate');

        $admin_user = \App\Models\User::create([
            'name' => 'Lester Lou Reyes',
            'email' => 'clarenista@gmail.com',
            'password' => bcrypt('lesterlou14'),
        ]);

        $encoder_user = \App\Models\User::create([
            'name' => 'Encoder',
            'email' => 'encoder@gmail.com',
            'password' => bcrypt('123'),
        ]);

        $supervisor_user = \App\Models\User::create([
            'name' => 'Supervisor',
            'email' => 'supervisor@gmail.com',
            'password' => bcrypt('123'),
        ]);

        $head_user = \App\Models\User::create([
            'name' => 'Head',
            'email' => 'head@gmail.com',
            'password' => bcrypt('123'),
        ]);

        $admin_user->assignRole($superadmin);
        $encoder_user->assignRole($encoder);
        $supervisor_user->assignRole($supervisor);
        $head_user->assignRole($head);


    }
}

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
        $verifier->givePermissionTo('edit facility');
        $verifier->givePermissionTo('delete facility');
        $verifier->givePermissionTo('create facility');
        
        $head = Role::create(['name' => 'head']);
        $head->givePermissionTo('approve');
        $head->givePermissionTo('issue certificate');
        $head->givePermissionTo('edit user');
        $head->givePermissionTo('delete user');
        $head->givePermissionTo('create user');

        $admin_user = \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123'),
        ]);

        $encoder_user = \App\Models\User::create([
            'name' => 'MARY KATHLENE S. TAMPIS',
            'email' => 'encoder@gmail.com',
            'password' => bcrypt('12341234'),
        ]);

        $verifier_user = \App\Models\User::create([
            'name' => 'EVANGELINE R. CASTILLO, RMT',
            'email' => 'verifier@gmail.com',
            'password' => bcrypt('12341234'),
        ]);
        $verifier_user1 = \App\Models\User::create([
            'name' => 'MARY ANN O. MANANSALA, RMT',
            'email' => 'verifier1@gmail.com',
            'password' => bcrypt('12341234'),
        ]);
        $verifier_user2 = \App\Models\User::create([
            'name' => 'JARED F. SALAZAR, RMT',
            'email' => 'verifier2@gmail.com',
            'password' => bcrypt('12341234'),
        ]);
        $verifier_user3 = \App\Models\User::create([
            'name' => 'CLARISE B. ROBOSA, RMT',
            'email' => 'verifier3@gmail.com',
            'password' => bcrypt('12341234'),
        ]);
        $verifier_user4 = \App\Models\User::create([
            'name' => 'PAUL VENZ SHEEN DG FUENTES, RMT',
            'email' => 'verifier4@gmail.com',
            'password' => bcrypt('12341234'),
        ]);

        $head_user = \App\Models\User::create([
            'name' => 'JENNIFER D. MERCADO, MD, MMHoA, FPSP',
            'email' => 'head@gmail.com',
            'password' => bcrypt('12341234'),
        ]);

        $admin_user->assignRole($admin);
        $encoder_user->assignRole($encoder);
        $verifier_user->assignRole($verifier);
        $verifier_user1->assignRole($verifier);
        $verifier_user2->assignRole($verifier);
        $verifier_user3->assignRole($verifier);
        $verifier_user4->assignRole($verifier);
        $head_user->assignRole($head);


    }
}

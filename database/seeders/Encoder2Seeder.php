<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class Encoder2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $encoder2 = Role::create(['name' => 'encoder2']);
        $encoder2->givePermissionTo('create facility');
        $encoder2->givePermissionTo('edit facility');

        $encoder2_user = \App\Models\User::create([
            'name' => 'Encoder 2',
            'email' => 'encoder2@gmail.com',
            'password' => bcrypt('12341234'),
        ]);
        
        $encoder2_user->assignRole(['encoder', 'encoder2']);
    }
}

<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class FacilityRoleSeeder extends Seeder
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
        Permission::create(['name' => 'apply pt']);
        Permission::create(['name' => 'send video']);

        $facility = Role::create(['name' => 'Facility']);
        $facility->givePermissionTo('apply pt');
        $facility->givePermissionTo('send video');
        $users = \App\Models\User::all();
        foreach ($users as $key => $value) {
            if ($value->username) {
                $users[$key]->assignRole($facility);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class FacilityRoleReSeed extends Seeder
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
        $facilityRole = Role::findByName('Facility');
        $users = \App\Models\User::where('username', '!=', null)->get();
        foreach ($users as $key => $value) {
            if ($value->username) {
                $users[$key]->assignRole($facilityRole);
            }
        }
    }
}

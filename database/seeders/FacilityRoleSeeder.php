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
        if (self::isRoleExist('Facility')) {

            $facilityRole = Role::findByName('Facility');
        } else {

            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            Permission::create(['name' => 'apply pt']);
            Permission::create(['name' => 'send video']);

            $facilityRole = Role::create(['name' => 'Facility']);
            $facilityRole->givePermissionTo('apply pt');
            $facilityRole->givePermissionTo('send video');
        }
        $users = \App\Models\User::where('username', '!=', null)->get();
        foreach ($users as $key => $value) {
            if ($value->username) {
                $users[$key]->assignRole($facilityRole);
            }
        }
    }

    function isRoleExist($role_name)
    {
        return Count(Role::findByName($role_name)->get()) > 0;
    }
}

<?php

namespace App\Library;

use App\Models\Facility;
use App\Models\User;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserCredential
{
    private $facility;
    function __construct($id)
    {
        $this->facility = Facility::find($id);
    }

    public function getFacility()
    {
        return $this->facility;
    }

    public function createUser()
    {
        $faker = Faker::create();
        $parsedId = sprintf('%05d', $this->facility->id);
        $regionNameArr = explode(' ', $this->facility->region_details->name);
        if (count($regionNameArr) > 1)
            $parsedRegionName = $regionNameArr[0][0] . $regionNameArr[1];
        else
            $parsedRegionName = implode('_', $regionNameArr);
        $username = $parsedRegionName . '_' . $parsedId;

        $exists = User::where('username', $username)->orWhere('email', $this->facility->email)->first();
        // $existLast = $exists ? substr($exists->username, -1) : '';
        if (!$exists) {

            $user = User::create([
                'name' => utf8_encode($this->facility->name),
                'email' => $faker->email(),
                'username' => $username,
                'password' => bcrypt('facility1234')
            ]);
            $this->facility->update(['user_id' => $user->id]);
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            $facilityRole = Role::findByName('Facility');
            $user->assignRole($facilityRole);
            // $user->assignRole($facility);
            return 'success';
        }
        return 'failed';
        // return ;
    }
}

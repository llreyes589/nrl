<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Faker\Factory as Faker;



class FacilityUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        // Permission::create(['name' => 'apply pt']);
        // Permission::create(['name' => 'send video']);

        // $facility = Role::create(['name' => 'Facility']);
        // $facility->givePermissionTo('apply pt');
        // $facility->givePermissionTo('send video');
        // name
        //             $table->string('name');
        // $table->string('email')->unique();
        // $table->timestamp('email_verified_at')->nullable();
        // $table->string('password');
        $facilities = Facility::with('region_details')->get();
        foreach ($facilities as $key => $value) {
            $parsedId = sprintf('%05d', $value->id);
            $regionNameArr = explode(' ', $value->region_details->name);
            if (count($regionNameArr) > 1)
                $parsedRegionName = $regionNameArr[0][0] . $regionNameArr[1];
            else
                $parsedRegionName = implode('_', $regionNameArr);
            $username = $parsedRegionName . '_' . $parsedId;
            $exists = User::where('username', $username)->orWhere('email', $value->email)->first();
            // $existLast = $exists ? substr($exists->username, -1) : '';
            if (!$exists) {

                $user = User::create([
                    'name' => utf8_encode($value->name),
                    'email' => $faker->email(),
                    'username' => $username,
                    'password' => bcrypt('facility1234')
                ]);
                Facility::find($value->id)->update(['user_id' => $user->id]);
                // $user->assignRole($facility);
            }
        }
    }
}

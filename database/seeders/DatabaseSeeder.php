<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RegionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([
        //     PermissionsSeeder::class,
        //     RegionsSeeder::class
        // ]);
        $csv = $this->command->ask('Please enter the csv filename. Ex: dtl.csv !!');
        $year = $this->command->ask('Please enter year. Ex: 2026');

        $this->call(FacilitiesSeeder26::class, false, compact('csv', 'year'));


    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Region::create([
            'name' => 'Region I',
        ]);
        \App\Models\Region::create([
            'name' => 'Region II',
        ]);
        \App\Models\Region::create([
            'name' => 'Region III',
        ]);
        \App\Models\Region::create([
            'name' => 'Region IV-A',
        ]);
        \App\Models\Region::create([
            'name' => 'Region IV-B',
        ]);
        \App\Models\Region::create([
            'name' => 'Region V',
        ]);
        \App\Models\Region::create([
            'name' => 'Region VI',
        ]);
        \App\Models\Region::create([
            'name' => 'Region VII',
        ]);
        \App\Models\Region::create([
            'name' => 'Region VIII',
        ]);
        \App\Models\Region::create([
            'name' => 'Region IX',
        ]);
        \App\Models\Region::create([
            'name' => 'Region X',
        ]);
        \App\Models\Region::create([
            'name' => 'Region XI',
        ]);
        \App\Models\Region::create([
            'name' => 'Region XII',
        ]);
        \App\Models\Region::create([
            'name' => 'NCR',
        ]);
        \App\Models\Region::create([
            'name' => 'CAR',
        ]);
        \App\Models\Region::create([
            'name' => 'ARMM',
        ]);
        \App\Models\Region::create([
            'name' => 'CARAGA',
        ]);
    }
}

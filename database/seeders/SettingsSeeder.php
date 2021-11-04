<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Setting::create([
            'certificate_theme' => 'CY 2021 PROFICIENCY TESTING SCHEME for SCREENING DRUGS OF ABUSE TESTING',
            'certificate_given_at' => '2021-11-26',
            'updated_by' => 1
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Facility::truncate();
        $csvFile = fopen(base_path("database/seeders/dtl.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Facility::create([
                    "region_id" => $data['0'],
                    "accreditation_no" => $data['1'],
                    "name" => utf8_encode($data['2']),
                    "address" => utf8_encode($data['3']),
                    "contact_no" => $data['4'],
                    "email" => $data['5'],
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}

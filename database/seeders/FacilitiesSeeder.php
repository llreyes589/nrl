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
                    "name" => utf8_encode($data['3']),
                    "address" => utf8_encode($data['4']),
                    "city" => utf8_encode($data['5']),
                    "contact_no" => $data['6'],
                    "head_of_lab" =>utf8_encode($data['7']),
                    "email" => utf8_encode($data['8']),
                    "lab_email" => utf8_encode($data['9']),
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}

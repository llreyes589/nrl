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
                $facility = Facility::create([
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
                $facility->certificate()->create([
                    'facility_id' => $facility->id,
                    'or_no' =>  $data['10'],
                    'certificate_no' =>  $data['18'].$data['19'],
                    'validity' =>  '2022-12-31',
                    'performance' =>  $data['20'],
                    'key' => md5(microtime())
                ]);
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}

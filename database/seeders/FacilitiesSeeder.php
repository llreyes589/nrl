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
        $csvFile = fopen(base_path("database/seeders/wtl.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                $facility = Facility::create([
                    "region_id" => $data['0'],
                    "accreditation_no" => $data['3'],
                    "name" => utf8_encode($data['4']),
                    "address" => utf8_encode($data['5']),
                    "contact_no" => $data['6'],
                    "email" => $data['7'],
                    ]);    
                $facility->certificate()->create([
                    'facility_id' => $facility->id,
                    "or_no" => $data['8'],
                    "certificate_no" => $data['11'],
                    "performance" => $data['13'],
                    "validity" => '2022-12-31',
                    'key' => md5(microtime())
                ]);
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}

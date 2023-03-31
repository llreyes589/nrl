<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\Certificate;
use Illuminate\Support\Str;

class FacilitiesSeeder23 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Facility::truncate();
        Certificate::truncate();
        $csvFile = fopen(base_path("database/seeders/wtl23.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                $facility = Facility::create([
                    "region_id" => $data['0'],
                    "accreditation_no" => Facility::where('accreditation_no', $data[3])->exists() ? Str::random(12) : $data[3],
                    "name" => utf8_encode($data['4']),
                    "address" => utf8_encode($data['5']),
                    "contact_no" => $data['6'],
                    "email" => $data['7'],
                ]);
                $facility->certificate()->create([
                    'facility_id' => $facility->id,
                    "or_no" => Certificate::where('or_no', $data['8'])->exists() ? Str::random(12) : $data[8],
                    "certificate_no" => Str::random(12),
                    "performance" => $data['13'],
                    "validity" => '2023-12-31',
                    'key' => md5(microtime()),
                    'certificate_template_id' => 2
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}

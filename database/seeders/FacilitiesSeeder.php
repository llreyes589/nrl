<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\Certificate;
use Illuminate\Support\Str;
use Faker\Factory as Faker;


class FacilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        Facility::truncate();
        Certificate::truncate();
        $csvFile = fopen(base_path("database/seeders/dtl.csv"), "r");

        $firstline = true;
        // Get current data from items table
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            // if (in_array(utf8_encode($data[1]->title),  Facility::select('accreditation_no')->get()->toArray()))
            //     continue;
            // print_r(in_array($data[1],) ?  $data[1] : Str::random(12));
            // $accred_no = in_array($data[1], Facility::select('accreditation_no')->get()->toArray()) ? Str::random(12) : $data[1];
            $emailExists = Facility::where('email', utf8_encode($data['8']))->exists();
            $labEmailExists = Facility::where('lab_email', utf8_encode($data['9']))->exists();
            if (!$firstline) {
                $facility = Facility::create([
                    "region_id" => $data['0'],
                    "accreditation_no" => Facility::where('accreditation_no', $data[1])->exists() ? Str::random(12) : $data[1],
                    "name" => utf8_encode($data['3']),
                    "address" => utf8_encode($data['4']),
                    "city" => utf8_encode($data['5']),
                    "contact_no" => $data['6'],
                    "head_of_lab" => utf8_encode($data['7']),
                    "email" => utf8_encode($data['8']) === '' || $emailExists ?  $data[0] . $faker->email() : utf8_encode($data['8']),
                    "lab_email" => utf8_encode($data['9']) === '' || $labEmailExists ?  $data[0] . $faker->email() : utf8_encode($data['9']),
                ]);
                $facility->certificate()->create([
                    'facility_id' => $facility->id,
                    'or_no' =>  $data['10'],
                    'certificate_no' =>  $data['18'] . $data['19'],
                    'validity' =>  '2022-12-31',
                    'performance' =>  $data['20'],
                    'key' => md5(microtime())
                ]);
                echo 'Facility added:' . $facility->id . PHP_EOL;
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}

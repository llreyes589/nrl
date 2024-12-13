<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class FacilitiesSeeder25 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $template = CertificateTemplate::create([
            'certificate_theme' => 'CY 2025 PROFICIENCY TESTING SCHEME for SCREENING DRUGS OF ABUSE TESTING',
            'certificate_given_at' => '2025-12-31',
            'year' => '2025',
            'updated_by' => 8,
            'director_name' => 'ALFONSO G. NUÑEZ III, MD, FPCS, MMHoA',
            'director_position' => 'Medical Center Chief II',
            'director_designation' => 'East Avenue Medical Center',
            'director_signature_path' => 'images/alfonso.png'
        ]);
        // Facility::truncate();
        // Certificate::truncate();
        $csvFile = fopen(base_path("database/seeders/dtl25.csv"), "r");

        $firstline = true;
        // Get current data from items table
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            // if (in_array(utf8_encode($data[1]->title),  Facility::select('accreditation_no')->get()->toArray()))
            //     continue;
            // print_r(in_array($data[1],) ?  $data[1] : Str::random(12));
            // $accred_no = in_array($data[1], Facility::select('accreditation_no')->get()->toArray()) ? Str::random(12) : $data[1];
            if (!$firstline) {
                $facility = Facility::create([
                    "region_id" => $data['0'],
                    "accreditation_no" => Facility::withTrashed()->where('accreditation_no', $data[1])->exists() ? Str::random(12) : $data[1],
                    "name" => utf8_encode($data['3']),
                    "address" => utf8_encode($data['4']),
                    "city" => utf8_encode($data['5']),
                    "contact_no" => $data['6'],
                    "head_of_lab" => utf8_encode($data['7']),
                    "email" => utf8_encode($data['8']),
                    "lab_email" => utf8_encode($data['9']),
                ]);
                $facility->certificate()->create([
                    'facility_id' => $facility->id,
                    'or_no' => Certificate::where('or_no', $data['10'])->exists() ? Str::random(12) : $data['10'],
                    'certificate_no' =>  Certificate::where('certificate_no', $data['18'])->exists() ? Str::random(12) : $data['18'],
                    'validity' =>  '2024-12-31',
                    'performance' =>  $data['19'],
                    'key' => md5(microtime()),
                    'certificate_template_id' => $template->id
                ]);
                echo 'done ' . $facility->id . PHP_EOL;
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
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
        // Facility::truncate();

        $template = CertificateTemplate::create([
            'certificate_theme' => 'CY 2025 PROFICIENCY TESTING SCHEME for SCREENING DRUGS OF ABUSE TESTING',
            'certificate_given_at' => '2025-03-08',
            'year' => '2025',
            'updated_by' => 8,
            'director_name' => 'ALFONSO G. NUÑEZ III, MD, FPCS, MMHoA',
            'director_position' => 'Medical Center Chief II',
            'director_designation' => 'East Avenue Medical Center',
            'director_signature_path' => 'images/alfonso.png'
        ]);
        // Certificate::truncate();
        $csvFile = fopen(base_path("database/seeders/wtl25.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                $facility = Facility::create([
                    "region_id" => $data['0'],
                    'city' => utf8_encode($data['1']),
                    "accreditation_no" => Facility::withTrashed()->where('accreditation_no', '=', $data[3])->exists() ? Str::random(12) : $data[3],
                    "name" => utf8_encode($data['4']),
                    "address" => utf8_encode($data['5']),
                    "contact_no" => $data['6'],
                    "email" => utf8_encode($data['7']),
                    "lab_email" => utf8_encode($data['8']),
                    "head_of_lab" => utf8_encode($data['9']),
                ]);
                $facility->certificate()->create([
                    'facility_id' => $facility->id,
                    "or_no" => Certificate::where('or_no', '=', $data['10'])->exists() ? Str::random(12) : $data[10],
                    "certificate_no" => Certificate::where('or_no', '=', $data['13'])->exists() ? Str::random(12) : $data['13'],
                    "performance" => $data['15'],
                    "validity" => '2025-12-31',
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

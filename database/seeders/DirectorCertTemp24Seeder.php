<?php

namespace Database\Seeders;

use App\Models\CertificateTemplate;
use Illuminate\Database\Seeder;

class DirectorCertTemp24Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CertificateTemplate::create([
            'certificate_theme' => 'CY 2023 PROFICIENCY TESTING SCHEME for SCREENING DRUGS OF ABUSE TESTING',
            'certificate_given_at' => '2024-03-08',
            'year' => '2024',
            'updated_by' => 8,
            'director_name' => 'ALFONSO	G. NUÑEZ III, MD, FPCS, MMHoA',
            'director_position' => 'Medical Center Chief II',
            'director_designation' => 'East Avenue Medical Center',
            'director_signature_path' => 'images/alfonso.png'
        ]);
    }
}

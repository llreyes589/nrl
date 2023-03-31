<?php

namespace Database\Seeders;

use App\Models\CertificateTemplate;
use Illuminate\Database\Seeder;

class DirectorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $directors = [
            [
                'director_name' => 'ATTY. NICOLAS B. LUTERO III, CESO I',
                'director_position' => 'Director IV',
                'director_designation' => 'Health Facilities and Services Regulatory Bureau',
                'director_signature_path' => 'images/lutero.png'
            ],
            [
                'director_name' => 'Atty. CHARADE B. MERCADO-GRANDE, MPSA',
                'director_position' => 'Assistant Secretary / Director IV',
                'director_designation' => 'Health Facility Services & Regulatory Bureau',
                'director_signature_path' => 'images/grande.png'
            ],
        ];

        $cert_templates = CertificateTemplate::all();
        $cert_templates[0]->update($directors[0]);
        $cert_templates[1]->update($directors[1]);
    }
}

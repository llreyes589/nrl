<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use Illuminate\Database\Seeder;

class CertificateTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CertificateTemplate::create([
            'certificate_theme' => 'CY 2021 PROFICIENCY TESTING SCHEME for WATER MICROBIOLOGICAL TESTING LABORATORY',
            'certificate_given_at' => '2021-11-26',
            'year' => '2022',
            'updated_by' => 8
        ]);
        CertificateTemplate::create([
            'certificate_theme' => 'CY 2021 PROFICIENCY TESTING SCHEME for WATER MICROBIOLOGICAL TESTING LABORATORY',
            'certificate_given_at' => '2022-11-26',
            'year' => '2023',
            'updated_by' => 8
        ]);

        $certs = Certificate::all();
        foreach ($certs as $c) {
            $c->update(['certificate_template_id' => 1]);
        }
    }
}

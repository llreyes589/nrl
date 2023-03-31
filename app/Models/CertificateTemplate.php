<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_theme', 'certificate_given_at', 'updated_by', 'year', 'director_name',
        'director_position',
        'director_designation',
        'director_signature_path'
    ];
}

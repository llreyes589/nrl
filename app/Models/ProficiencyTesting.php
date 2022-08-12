<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProficiencyTesting extends Model
{
    use HasFactory;

    protected $fillable = [
        'sdtl',
        'cycle',
        'method_used'
    ];

    public function applications()
    {
        return $this->hasMany('\App\Models\ProficiencyTestingApplication', 'proficiency_testing_id', 'id');
    }
}

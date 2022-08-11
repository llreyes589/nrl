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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'accreditation_no', 
        'name', 
        'address', 
        'city', 
        'contact_no', 
        'email', 
        'lab_email', 
        'or_no', 
        'validity', 
    ];
}

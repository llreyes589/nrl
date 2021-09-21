<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

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
        'deleted_at'
    ];
}

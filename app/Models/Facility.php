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
        'region_id', 
        'head_of_lab', 
        'contact_no', 
        'email', 
        'lab_email', 
        'or_no', 
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    function certificate(){
        return $this->hasOne(Certificate::class)->orderBy('created_at', 'desc');
    }

    function region_details(){
        return $this->hasOne('App\Models\Region', 'id', 'region_id');
    }
}

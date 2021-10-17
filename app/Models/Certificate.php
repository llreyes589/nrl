<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = ['facility_id', 'or_no', 'validity','performance', 'verified_by', 'verified_at', 'endorsed_by', 'endorsed_at', 'approved_by', 'approved_at'];
}

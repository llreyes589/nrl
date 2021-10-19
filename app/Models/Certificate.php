<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Facility;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = ['facility_id', 'or_no', 'validity','performance', 'verified_by', 'verified_at', 'endorsed_by', 'endorsed_at', 'approved_by', 'approved_at', 'facility_verified_by', 'facility_verified_at', 'issued_by', 'issued_at'];

    function facility(){
        return $this->belongsTo(Facility::class);
    }
}

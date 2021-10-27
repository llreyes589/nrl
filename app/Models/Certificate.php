<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Facility;
use App\Models\User;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id', 
        'or_no',
        'validity',
        'performance', 
        'prepared_by', 
        'prepared_at', 
        'verified_by', 
        'verified_at', 
        'approved_by',
        'approved_at', 
        'facility_verified_by', 
        'facility_verified_at', 
        'issued_by', 
        'issued_at'
    ];

    function facility(){
        return $this->belongsTo(Facility::class);
    }

    function prepared_by_details(){
        return $this->belongsTo('\App\Models\User', 'prepared_by', 'id');
    }
    function verified_by_details(){
        return $this->belongsTo('\App\Models\User', 'verified_by', 'id');
    }
    function approved_by_details(){
        return $this->belongsTo('\App\Models\User', 'approved_by', 'id');
    }
}

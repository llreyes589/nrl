<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specimen extends Model
{
    use HasFactory;

    protected $fillable = [
        'proficiency_testing_application_id',
        'unboxing_video_path',
        'sent_by',
        'courier',
        'tracking_number',
        'accepted_bottles', 'reject_description'
    ];
}

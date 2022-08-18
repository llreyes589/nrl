<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;

class ProficiencyTestingApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'proficiency_testing_id',
        'test_method_used',
        'cutoff_value',
        'methamphetamine',
        'tetrahydrocannabinol',
        'receipt_path',
        'unboxing_video_path',
        'specimen_sent',
        'verified_payment',
        'result_path'
    ];

    public function users()
    {
        return $this->hasMany('\App\Models\User', 'user_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('\App\Models\User', 'user_id', 'id');
    }

    public function pt()
    {
        return $this->belongsTo('\App\Models\ProficiencyTesting', 'proficiency_testing_id', 'id');
    }

    public function specimens()
    {
        return $this->hasMany('\App\Models\Specimen', 'proficiency_testing_application_id', 'id');
    }
}

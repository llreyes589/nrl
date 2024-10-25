<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProficiencyTestingApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'proficiency_testing_id',
        'test_method_used',
        'cutoff_value',
        'methamphetamine',
        'tetrahydrocannabinol',
        'receipt_path',
        'unboxing_video_path',
        'specimen_sent',
        'verified_payment',
        'result_path',
        'score',
        'scored_by',
        'scored_at',
        'receipt_uploaded_at',
        'result_uploaded_at',
        'verified_payment_at',
        'mode_of_payment',
        'deleted_at',
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
    public function certificate()
    {
        return $this->hasOne('\App\Models\Certificate', 'proficiency_testing_application_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_theme', 'certificate_given_at', 'updated_by',
        'pt_id'
    ];

    public function pt()
    {
        return $this->belongsTo('\App\Models\ProficiencyTesting', 'pt_id', 'id');
    }
}

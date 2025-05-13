<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;
    protected $fillable = ['pt_application_id', 'status_id', 'file_path', 'reject_reason'];

    public function status_details(){
        return $this->hasOne('\App\Models\Status', 'id', 'status_id');
    }
}

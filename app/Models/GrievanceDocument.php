<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrievanceDocument extends Model
{
    protected $fillable = ['grievance_id', 'path', 'original_name', 'mime'];

    public function grievance()
    {
        return $this->belongsTo(Grievance::class);
    }
}

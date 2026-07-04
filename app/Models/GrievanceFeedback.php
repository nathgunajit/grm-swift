<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrievanceFeedback extends Model
{
    protected $table = 'grievance_feedback';

    protected $fillable = [
        'grievance_id', 'informed', 'heard_respectfully', 'response_time_ok',
        'satisfaction', 'transparency', 'official_behavior', 'feel_safe',
        'rating', 'comments',
    ];

    protected $casts = [
        'informed' => 'boolean',
        'heard_respectfully' => 'boolean',
        'response_time_ok' => 'boolean',
        'feel_safe' => 'boolean',
    ];

    public function grievance()
    {
        return $this->belongsTo(Grievance::class);
    }
}

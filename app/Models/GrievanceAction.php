<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrievanceAction extends Model
{
    protected $fillable = [
        'grievance_id', 'user_id', 'action', 'from_level', 'to_level', 'remarks',
    ];

    public function grievance()
    {
        return $this->belongsTo(Grievance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function label(): string
    {
        return ucfirst($this->action);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAssignment extends Model
{
    protected $fillable = [
        'user_id', 'user_type_id', 'cpiu_id', 'district_id', 'beel_id',
        'assign_date', 'relieving_date',
    ];

    protected $casts = [
        'assign_date' => 'date',
        'relieving_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function cpiu()
    {
        return $this->belongsTo(Cpiu::class);
    }

    public function beel()
    {
        return $this->belongsTo(Beel::class);
    }
}

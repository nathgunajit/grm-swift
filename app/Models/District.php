<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['name', 'code', 'cpiu_id', 'is_active'];

    public function cpiu()
    {
        return $this->belongsTo(Cpiu::class);
    }

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    public function revenueCircles()
    {
        return $this->hasMany(RevenueCircle::class);
    }

    public function beels()
    {
        return $this->hasMany(Beel::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cpiu extends Model
{
    protected $table = 'cpius';

    protected $fillable = ['name', 'code', 'zone_id', 'is_active'];

    public function beels()
    {
        return $this->hasMany(Beel::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}

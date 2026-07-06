<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cpiu extends Model
{
    protected $table = 'cpius';

    protected $fillable = ['name', 'code', 'is_active'];

    public function beels()
    {
        return $this->hasMany(Beel::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}

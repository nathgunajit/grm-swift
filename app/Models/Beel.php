<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beel extends Model
{
    protected $fillable = ['name', 'district_id', 'block_id', 'cpiu_id', 'latitude', 'longitude', 'is_active'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function cpiu()
    {
        return $this->belongsTo(Cpiu::class);
    }
}

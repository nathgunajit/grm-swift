<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = ['district_id', 'name', 'is_active'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}

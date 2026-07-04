<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = ['name', 'code', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function cpius()
    {
        return $this->hasMany(Cpiu::class);
    }
}

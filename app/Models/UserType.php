<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

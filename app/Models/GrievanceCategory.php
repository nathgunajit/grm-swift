<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrievanceCategory extends Model
{
    protected $fillable = ['code', 'name', 'is_sensitive', 'is_active'];

    protected $casts = [
        'is_sensitive' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function grievances()
    {
        return $this->hasMany(Grievance::class, 'category_id');
    }
}

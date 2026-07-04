<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $fillable = ['name', 'level', 'district_id', 'cpiu_id', 'is_active'];

    public function members()
    {
        return $this->hasMany(CommitteeMember::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function cpiu()
    {
        return $this->belongsTo(Cpiu::class);
    }

    public function womenPercentage(): int
    {
        $total = $this->members->count();
        if ($total === 0) {
            return 0;
        }
        return (int) round($this->members->where('is_woman', true)->count() / $total * 100);
    }
}

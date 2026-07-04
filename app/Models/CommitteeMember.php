<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    protected $fillable = ['committee_id', 'name', 'designation', 'role', 'is_woman'];

    protected $casts = ['is_woman' => 'boolean'];

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }
}

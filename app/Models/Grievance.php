<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Grievance extends Model
{
    protected $fillable = [
        'tracking_id', 'acknowledgment_no', 'mode_of_receipt', 'category_id',
        'name', 'gender', 'age', 'caste', 'mobile', 'email', 'address',
        'place_village', 'beel_id', 'district_id', 'description',
        'is_anonymous', 'is_confidential', 'is_sensitive',
        'status', 'current_level', 'due_at', 'resolution', 'resolved_at',
        'registered_by', 'assigned_to',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_confidential' => 'boolean',
        'is_sensitive' => 'boolean',
        'due_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    // SLA days per level (from the GRM manual: 7 / 15 / 15).
    public const SLA_DAYS = [1 => 7, 2 => 15, 3 => 15];

    public const STATUS_LABELS = [
        'registered' => 'Registered',
        'under_review' => 'Under Review',
        'escalated' => 'Escalated',
        'resolved' => 'Resolved',
        'closed' => 'Closed',
        'reopened' => 'Reopened',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(GrievanceCategory::class, 'category_id');
    }

    public function beel(): BelongsTo
    {
        return $this->belongsTo(Beel::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(GrievanceDocument::class);
    }

    public function actions(): HasMany
    {
        return $this->hasMany(GrievanceAction::class)->orderBy('created_at');
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(GrievanceFeedback::class);
    }

    public function isOverdue(): bool
    {
        return $this->due_at
            && ! in_array($this->status, ['resolved', 'closed'], true)
            && $this->due_at->isPast();
    }

    public function levelLabel(): string
    {
        return match ((int) $this->current_level) {
            1 => 'Level I — Field / Beel',
            2 => 'Level II — Cluster / CPIU',
            3 => 'Level III — PIU',
            default => 'Level '.$this->current_level,
        };
    }
}

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

    /**
     * Colour-coded due status for the grievance list.
     * Returns [tailwind classes, label].
     */
    public function dueBadge(): array
    {
        if (in_array($this->status, ['resolved', 'closed'], true)) {
            return ['bg-slate-100 text-slate-500', 'Done'];
        }
        if (! $this->due_at) {
            return ['bg-slate-100 text-slate-500', '—'];
        }
        if ($this->due_at->isPast()) {
            return ['bg-rose-100 text-rose-700', 'Overdue'];
        }
        $days = (int) ceil(now()->diffInHours($this->due_at) / 24);
        if ($days <= 3) {
            return ['bg-amber-100 text-amber-700', $days.'d left'];
        }

        return ['bg-emerald-100 text-emerald-700', $days.'d left'];
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

    /**
     * Restrict the query to what the given user may see, based on their role
     * and jurisdiction (beel / district / CPIU / level).
     */
    public function scopeVisibleTo($query, User $user)
    {
        return match ($user->role()) {
            // Full visibility.
            'super_admin', 'pmu_admin', 'piu_officer' => $query,
            // CPIU officer: their CPIU's beels, focus on Level II+.
            'cpiu_officer' => $query->whereHas('beel', fn ($q) => $q->where('cpiu_id', $user->cpiu_id)),
            // District-level officials.
            'ssgc', 'dfdo', 'bdc_facilitator' => $query->where('district_id', $user->district_id),
            // Beel animator: only their beel.
            'beel_animator' => $query->where('beel_id', $user->beel_id),
            default => $query->whereRaw('1 = 0'),
        };
    }
}

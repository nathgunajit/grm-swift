<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'empid', 'name', 'email', 'mobile', 'password', 'designation',
        'office_address', 'user_type_id', 'cpiu_id', 'district_id', 'beel_id', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function cpiu(): BelongsTo
    {
        return $this->belongsTo(Cpiu::class);
    }

    public function beel(): BelongsTo
    {
        return $this->belongsTo(Beel::class);
    }

    public function assignments()
    {
        return $this->hasMany(UserAssignment::class);
    }

    /** Role slug helper (e.g. 'super_admin', 'ssgc', 'cpiu_officer'). */
    public function role(): ?string
    {
        return $this->userType?->slug;
    }

    public function hasRole(string|array $slugs): bool
    {
        $slugs = (array) $slugs;
        return in_array($this->role(), $slugs, true);
    }

    /**
     * Grievance actions each role may perform (from the module-visibility spec).
     * Actions: manual_entry, review, comment, escalate, resolve.
     */
    public const ROLE_GRIEVANCE_ACTIONS = [
        'beel_animator'   => ['manual_entry', 'review', 'comment', 'escalate'],
        'bdc_facilitator' => ['review', 'comment', 'escalate'],
        'ssgc'            => ['manual_entry', 'review', 'comment', 'escalate'],
        'dfdo'            => ['review', 'comment', 'escalate', 'resolve'],
        'cpiu_officer'    => ['review', 'comment', 'escalate', 'resolve'],
        'piu_officer'     => ['manual_entry', 'review', 'comment', 'resolve'],
        'pmu_admin'       => [], // view / monitoring only
        'super_admin'     => ['manual_entry', 'review', 'comment', 'escalate', 'resolve'],
    ];

    public function allowedGrievanceActions(): array
    {
        return self::ROLE_GRIEVANCE_ACTIONS[$this->role()] ?? [];
    }

    public function canGrievance(string $action): bool
    {
        return in_array($action, $this->allowedGrievanceActions(), true);
    }
}

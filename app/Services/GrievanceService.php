<?php

namespace App\Services;

use App\Models\Grievance;
use App\Models\GrievanceAction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GrievanceService
{
    /**
     * Generate a unique sequential tracking ID like GRM-2026-000001.
     */
    public function generateTrackingId(): string
    {
        $year = now()->year;

        return DB::transaction(function () use ($year) {
            $prefix = "GRM-{$year}-";
            $last = Grievance::where('tracking_id', 'like', $prefix.'%')
                ->lockForUpdate()
                ->orderByDesc('tracking_id')
                ->value('tracking_id');

            $next = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

            return $prefix.str_pad((string) $next, 6, '0', STR_PAD_LEFT);
        });
    }

    public function generateAcknowledgmentNo(string $trackingId): string
    {
        return 'ACK-'.substr($trackingId, 4);
    }

    /**
     * SLA due date for the given level, counted from a base time.
     */
    public function dueDateForLevel(int $level, ?Carbon $from = null): Carbon
    {
        $days = Grievance::SLA_DAYS[$level] ?? 7;

        return ($from ?? now())->copy()->addDays($days);
    }

    /**
     * Record an entry on the grievance timeline / audit trail.
     */
    public function logAction(Grievance $grievance, string $action, ?int $userId = null, ?string $remarks = null, ?int $fromLevel = null, ?int $toLevel = null): GrievanceAction
    {
        return $grievance->actions()->create([
            'user_id' => $userId,
            'action' => $action,
            'from_level' => $fromLevel,
            'to_level' => $toLevel,
            'remarks' => $remarks,
        ]);
    }

    /**
     * Escalate a grievance to the next level (max 3).
     */
    public function escalate(Grievance $grievance, ?int $userId = null, ?string $remarks = null): bool
    {
        if ($grievance->current_level >= 3) {
            return false;
        }

        $from = (int) $grievance->current_level;
        $to = $from + 1;

        $grievance->update([
            'current_level' => $to,
            'status' => 'escalated',
            'due_at' => $this->dueDateForLevel($to),
            'resolved_at' => null,
        ]);

        $this->logAction($grievance, 'escalated', $userId, $remarks, $from, $to);

        return true;
    }

    /**
     * Mark a grievance resolved with the given resolution text.
     */
    public function resolve(Grievance $grievance, string $resolution, ?int $userId = null): void
    {
        $grievance->update([
            'status' => 'resolved',
            'resolution' => $resolution,
            'resolved_at' => now(),
        ]);

        $this->logAction($grievance, 'resolved', $userId, $resolution, $grievance->current_level, $grievance->current_level);
    }
}

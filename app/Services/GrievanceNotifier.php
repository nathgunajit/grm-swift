<?php

namespace App\Services;

use App\Models\Grievance;
use App\Models\User;
use App\Notifications\GrievanceAdminAlert;
use App\Notifications\GrievanceRegistered;
use App\Notifications\GrievanceUpdated;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;

/**
 * Central place that fans a grievance event out to:
 *   1. the complainant (email + demo SMS), and
 *   2. the officials who can act on / monitor that grievance (in-app bell).
 */
class GrievanceNotifier
{
    /** New grievance registered (online or manual). */
    public function registered(Grievance $grievance): void
    {
        $this->notifyComplainant($grievance, new GrievanceRegistered($grievance));

        $this->notifyOfficers(
            $grievance,
            'New grievance registered',
            $grievance->tracking_id.' — '.($grievance->category?->name ?? 'Grievance')
                .' • '.($grievance->place_village ?? $grievance->district?->name ?? '—'),
            'inbox',
        );
    }

    /**
     * An action was taken. When $tellComplainant is true the complainant is also
     * emailed/SMSed (use for escalate / resolve / reopen — not for internal notes).
     */
    public function actionTaken(
        Grievance $grievance,
        string $title,
        string $detail,
        bool $tellComplainant = false,
        string $icon = 'inbox',
    ): void {
        if ($tellComplainant) {
            $this->notifyComplainant($grievance, new GrievanceUpdated($grievance, $title, $detail));
        }

        $this->notifyOfficers($grievance, $title, $grievance->tracking_id.' — '.$detail, $icon);
    }

    private function notifyComplainant(Grievance $grievance, $notification): void
    {
        if ($grievance->is_anonymous || (! $grievance->email && ! $grievance->mobile)) {
            return;
        }

        $target = new AnonymousNotifiable;
        if ($grievance->email) {
            $target->route('mail', $grievance->email);
        }
        if ($grievance->mobile) {
            $target->route('sms', $grievance->mobile);
        }

        $target->notify($notification);
    }

    private function notifyOfficers(Grievance $grievance, string $title, string $body, string $icon): void
    {
        $officers = $this->officersFor($grievance);
        if ($officers->isNotEmpty()) {
            Notification::send($officers, new GrievanceAdminAlert($grievance, $title, $body, $icon));
        }
    }

    /**
     * Officials who should see the grievance, mirroring Grievance::scopeVisibleTo:
     * full-visibility roles always, plus role-and-jurisdiction matches.
     */
    private function officersFor(Grievance $grievance)
    {
        $cpiuId = $grievance->district?->cpiu_id;

        return User::query()
            ->where('is_active', true)
            ->where(function ($q) use ($grievance, $cpiuId) {
                // Full-visibility roles always.
                $q->whereHas('userType', fn ($t) => $t->whereIn('slug', ['super_admin', 'pmu_admin', 'piu_officer']));

                // District-scoped officials (only if the grievance has a district).
                if ($grievance->district_id) {
                    $q->orWhere(function ($q) use ($grievance) {
                        $q->whereHas('userType', fn ($t) => $t->whereIn('slug', ['ssgc', 'dfdo', 'bdc_facilitator']))
                            ->where('district_id', $grievance->district_id);
                    });
                }

                // Beel animator (only if the grievance has a beel).
                if ($grievance->beel_id) {
                    $q->orWhere(function ($q) use ($grievance) {
                        $q->whereHas('userType', fn ($t) => $t->where('slug', 'beel_animator'))
                            ->where('beel_id', $grievance->beel_id);
                    });
                }

                // CPIU officer owning the grievance's district.
                if ($cpiuId) {
                    $q->orWhere(function ($q) use ($cpiuId) {
                        $q->whereHas('userType', fn ($t) => $t->where('slug', 'cpiu_officer'))
                            ->where('cpiu_id', $cpiuId);
                    });
                }
            })
            ->get();
    }
}

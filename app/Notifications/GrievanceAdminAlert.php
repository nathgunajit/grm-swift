<?php

namespace App\Notifications;

use App\Models\Grievance;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * In-app (database) notification for officials — surfaced in the admin bell.
 * Fired when a grievance is registered or an action is taken on it.
 */
class GrievanceAdminAlert extends Notification
{
    use Queueable;

    public function __construct(
        public Grievance $grievance,
        public string $title,
        public string $body,
        public string $icon = 'inbox',
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'grievance_id' => $this->grievance->id,
            'tracking_id' => $this->grievance->tracking_id,
            'title' => $this->title,
            'body' => $this->body,
            'icon' => $this->icon,
            'url' => route('admin.grievances.show', $this->grievance),
        ];
    }
}

<?php

namespace App\Notifications;

use App\Models\Grievance;
use App\Notifications\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to the complainant when a meaningful action is taken on their grievance
 * (escalated, resolved, reopened). Email + SMS (demo gateway).
 */
class GrievanceUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public Grievance $grievance,
        public string $headline,
        public string $detail,
    ) {
    }

    public function via(object $notifiable): array
    {
        $channels = [];
        if ($notifiable->routeNotificationFor('mail')) {
            $channels[] = 'mail';
        }
        if ($notifiable->routeNotificationFor('sms')) {
            $channels[] = SmsChannel::class;
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $g = $this->grievance;

        return (new MailMessage)
            ->subject('SWIFT GRM — '.$this->headline.' ('.$g->tracking_id.')')
            ->greeting('Dear '.($g->name ?: 'Complainant').',')
            ->line($this->headline.' for your grievance '.$g->tracking_id.'.')
            ->line($this->detail)
            ->line('**Current status:** '.(Grievance::STATUS_LABELS[$g->status] ?? $g->status))
            ->action('View details', route('track'))
            ->salutation('— ARIAS Society, Government of Assam');
    }

    public function toSms(object $notifiable): array
    {
        $g = $this->grievance;

        return [
            'purpose' => $g->status,
            'message' => 'SWIFT GRM ('.$g->tracking_id.'): '.$this->headline.'. '
                .$this->detail.' Track at '.route('track').'. -ARIAS Society',
        ];
    }
}

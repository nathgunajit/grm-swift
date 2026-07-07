<?php

namespace App\Notifications;

use App\Models\Grievance;
use App\Notifications\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to the complainant when a grievance is registered — carries the
 * Tracking ID / Acknowledgment No. via email and SMS (demo gateway).
 */
class GrievanceRegistered extends Notification
{
    use Queueable;

    public function __construct(public Grievance $grievance)
    {
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
            ->subject('SWIFT GRM — Grievance Registered ('.$g->tracking_id.')')
            ->greeting('Dear '.($g->name ?: 'Complainant').',')
            ->line('Your grievance has been successfully registered with the Assam SWIFT Project Grievance Redressal Mechanism.')
            ->line('**Tracking ID:** '.$g->tracking_id)
            ->line('**Acknowledgment No.:** '.$g->acknowledgment_no)
            ->line('**Category:** '.$g->category?->name)
            ->line('**Status:** '.(Grievance::STATUS_LABELS[$g->status] ?? $g->status))
            ->line('**Registered on:** '.$g->created_at->format('d M Y, h:i A'))
            ->action('Track your grievance', route('track'))
            ->line('Please keep your Tracking ID safe — you can use it to track progress or download the acknowledgment slip.')
            ->salutation('— ARIAS Society, Government of Assam');
    }

    public function toSms(object $notifiable): array
    {
        $g = $this->grievance;

        return [
            'purpose' => 'registered',
            'message' => 'SWIFT GRM: Your grievance is registered. Tracking ID '.$g->tracking_id
                .' (Ack '.$g->acknowledgment_no.'). Track it at '.route('track').'. -ARIAS Society',
        ];
    }
}

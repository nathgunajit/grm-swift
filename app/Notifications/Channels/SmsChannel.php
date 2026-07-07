<?php

namespace App\Notifications\Channels;

use App\Services\SmsService;
use Illuminate\Notifications\Notification;

/**
 * Custom notification channel that routes a notification's `toSms()` string
 * through the SmsService (demo gateway). Register it by returning
 * SmsChannel::class from a notification's via() method.
 */
class SmsChannel
{
    public function __construct(private SmsService $sms)
    {
    }

    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toSms')) {
            return;
        }

        $to = $notifiable->routeNotificationFor('sms', $notification);
        if (! $to) {
            return;
        }

        $payload = $notification->toSms($notifiable);
        $message = is_array($payload) ? ($payload['message'] ?? '') : (string) $payload;
        $purpose = is_array($payload) ? ($payload['purpose'] ?? null) : null;

        if ($message !== '') {
            $this->sms->send($to, $message, $purpose);
        }
    }
}

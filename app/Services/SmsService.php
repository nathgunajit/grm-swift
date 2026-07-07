<?php

namespace App\Services;

use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * SMS gateway abstraction.
 *
 * Demo mode (default, config: services.sms.driver = 'log'): the message is
 * written to `sms_logs` and the application log instead of being sent. To go
 * live, set SMS_DRIVER=msg91 (or NIC) and fill the credentials — the sendViaMsg91()
 * stub shows the shape of a real call.
 */
class SmsService
{
    public function send(string $mobile, string $message, ?string $purpose = null): void
    {
        $mobile = preg_replace('/\D/', '', $mobile);
        if ($mobile === '') {
            return;
        }

        $driver = config('services.sms.driver', 'log');

        try {
            $status = $driver === 'msg91'
                ? $this->sendViaMsg91($mobile, $message)
                : 'sent (demo)';
        } catch (\Throwable $e) {
            Log::error('SMS send failed: '.$e->getMessage());
            $status = 'failed';
        }

        SmsLog::create([
            'mobile' => $mobile,
            'message' => $message,
            'purpose' => $purpose,
            'status' => $status,
            'gateway' => $driver,
        ]);

        Log::info("GRM SMS [{$driver}] to {$mobile}: {$message}");
    }

    /**
     * Live gateway stub (MSG91 transactional). Not exercised in demo mode.
     */
    private function sendViaMsg91(string $mobile, string $message): string
    {
        $response = Http::asForm()->post('https://api.msg91.com/api/sendhttp.php', [
            'authkey' => config('services.sms.key'),
            'sender' => config('services.sms.sender'),
            'route' => '4',
            'country' => '91',
            'mobiles' => $mobile,
            'message' => $message,
        ]);

        return $response->successful() ? 'sent' : 'failed';
    }
}

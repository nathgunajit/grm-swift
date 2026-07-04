<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    // Demo mode: OTP is returned/logged instead of sent via SMS.
    // Swap sendOtp()'s delivery for a real gateway (MSG91 / NIC SMS / Twilio) later.
    private const TTL_SECONDS = 300;

    public function send(Request $request)
    {
        $request->validate(['mobile' => ['required', 'regex:/^[6-9]\d{9}$/']]);
        $mobile = $request->input('mobile');

        $otp = (string) random_int(100000, 999999);
        Cache::put($this->key($mobile), $otp, self::TTL_SECONDS);

        Log::info("GRM demo OTP for {$mobile}: {$otp}");

        return response()->json([
            'status' => 'sent',
            'message' => 'OTP generated. In demo mode it is shown below (no SMS is sent).',
            'demo_otp' => $otp, // demo only — remove when a real gateway is wired in
            'expires_in' => self::TTL_SECONDS,
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/'],
            'otp' => ['required', 'digits:6'],
        ]);
        $mobile = $request->input('mobile');
        $expected = Cache::get($this->key($mobile));

        if (! $expected || ! hash_equals($expected, $request->input('otp'))) {
            return response()->json(['status' => 'invalid', 'message' => 'Incorrect or expired OTP.'], 422);
        }

        Cache::forget($this->key($mobile));
        $request->session()->put($this->verifiedKey($mobile), true);

        return response()->json(['status' => 'verified', 'message' => 'Mobile number verified.']);
    }

    public static function isVerified(Request $request, string $mobile): bool
    {
        return (bool) $request->session()->get('otp_verified_'.$mobile, false);
    }

    private function key(string $mobile): string
    {
        return 'otp_'.$mobile;
    }

    private function verifiedKey(string $mobile): string
    {
        return 'otp_verified_'.$mobile;
    }
}

<?php

namespace Tests\Feature;

use App\Models\Beel;
use App\Models\Grievance;
use App\Models\GrievanceCategory;
use App\Models\SmsLog;
use App\Models\User;
use App\Services\GrievanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function verifiedPayload(string $mobile): array
    {
        return [
            'mode_of_receipt' => 'online',
            'category_id' => GrievanceCategory::where('code', 2)->first()->id,
            'name' => 'Notify Tester',
            'mobile' => $mobile,
            'email' => 'tester@example.com',
            'address' => 'Addr',
            'place_village' => 'Village',
            'description' => 'Testing the notification fan-out on submission.',
        ];
    }

    public function test_online_submission_sms_and_admin_notification(): void
    {
        $mobile = '9876543210';

        // Verify OTP so the submission passes the gate.
        $otp = $this->postJson('/otp/send', ['mobile' => $mobile])->json('demo_otp');
        $this->postJson('/otp/verify', ['mobile' => $mobile, 'otp' => $otp])->assertOk();

        $this->post('/submit', $this->verifiedPayload($mobile))->assertRedirect();

        $grievance = Grievance::where('mobile', $mobile)->firstOrFail();

        // Complainant received a demo SMS carrying the tracking ID.
        $sms = SmsLog::where('mobile', $mobile)->first();
        $this->assertNotNull($sms);
        $this->assertStringContainsString($grievance->tracking_id, $sms->message);

        // Officers with visibility got an in-app (database) notification.
        $admin = User::where('email', 'admin@grmswift.local')->first();
        $this->assertTrue($admin->notifications()->count() > 0);
        $this->assertSame($grievance->id, $admin->notifications()->first()->data['grievance_id']);
    }

    public function test_resolve_notifies_complainant_and_officers(): void
    {
        $service = app(GrievanceService::class);
        $beel = Beel::where('name', 'Deepor Beel')->first(); // Kamrup
        $tid = $service->generateTrackingId();
        $grievance = Grievance::create([
            'tracking_id' => $tid,
            'acknowledgment_no' => $service->generateAcknowledgmentNo($tid),
            'mode_of_receipt' => 'online',
            'category_id' => GrievanceCategory::first()->id,
            'name' => 'Resolve Tester', 'mobile' => '9800000001',
            'place_village' => 'V', 'beel_id' => $beel->id, 'district_id' => $beel->district_id,
            'description' => 'Resolve notification test.',
            'status' => 'registered', 'current_level' => 1,
            'due_at' => $service->dueDateForLevel(1),
        ]);

        $dfdo = User::where('email', 'dfdo@grmswift.local')->first();
        $this->actingAs($dfdo)
            ->post(route('admin.grievances.resolve', $grievance), ['resolution' => 'Resolved for notification test.'])
            ->assertRedirect();

        // Complainant SMS on resolution.
        $this->assertTrue(SmsLog::where('mobile', '9800000001')->where('purpose', 'resolved')->exists());
        // DFDO's own bell has an alert too.
        $this->assertTrue($dfdo->notifications()->where('type', \App\Notifications\GrievanceAdminAlert::class)->count() > 0);
    }

    public function test_bell_page_loads(): void
    {
        $admin = User::where('email', 'admin@grmswift.local')->first();
        $this->actingAs($admin)->get(route('admin.notifications.index'))->assertOk();
    }
}

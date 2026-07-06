<?php

namespace Tests\Feature;

use App\Models\Beel;
use App\Models\Grievance;
use App\Models\GrievanceCategory;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class OtpAndZoneTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function basePayload(array $overrides = []): array
    {
        return array_merge([
            'mode_of_receipt' => 'online',
            'category_id' => GrievanceCategory::where('code', 2)->first()->id,
            'name' => 'OTP Tester',
            'mobile' => '9876543210',
            'address' => 'Addr',
            'place_village' => 'Village',
            'description' => 'Testing the OTP gate on submission.',
        ], $overrides);
    }

    public function test_non_anonymous_submission_is_blocked_without_otp(): void
    {
        $this->post('/submit', $this->basePayload())->assertSessionHasErrors('mobile');
        $this->assertSame(0, Grievance::where('name', 'OTP Tester')->count());
    }

    public function test_otp_send_and_verify_then_submit(): void
    {
        $mobile = '9876543210';

        $send = $this->postJson('/otp/send', ['mobile' => $mobile]);
        $send->assertOk()->assertJsonStructure(['demo_otp']);
        $otp = $send->json('demo_otp');

        // Wrong OTP is rejected.
        $this->postJson('/otp/verify', ['mobile' => $mobile, 'otp' => '000000'])->assertStatus(422);

        // Correct OTP verifies and sets the session flag.
        $this->postJson('/otp/verify', ['mobile' => $mobile, 'otp' => $otp])
            ->assertOk()->assertJson(['status' => 'verified']);
        $this->assertTrue(session('otp_verified_'.$mobile));
    }

    public function test_anonymous_submission_skips_otp(): void
    {
        $this->post('/submit', $this->basePayload([
            'is_anonymous' => '1',
            'name' => null,
            'mobile' => null,
        ]));

        $this->assertSame(1, Grievance::where('is_anonymous', true)->where('description', 'like', '%OTP gate%')->count());
    }

    public function test_districts_belong_to_a_cpiu_and_assignment_works(): void
    {
        $admin = User::where('email', 'admin@grmswift.local')->first();

        // Seeded relationship: districts map to a CPIU.
        $this->assertNotNull(\App\Models\District::whereNotNull('cpiu_id')->first());

        // Assign a free district to a CPIU via the CPIU update endpoint.
        $cpiu = \App\Models\Cpiu::first();
        $district = \App\Models\District::first();

        $this->actingAs($admin)->put(route('admin.cpius.update', $cpiu), [
            'name' => $cpiu->name,
            'code' => $cpiu->code,
            'district_ids' => [$district->id],
        ])->assertRedirect();

        $this->assertSame($cpiu->id, $district->fresh()->cpiu_id);
    }

    public function test_zone_routes_are_gone(): void
    {
        $this->assertFalse(\Illuminate\Support\Facades\Route::has('admin.zones.index'));
    }

    public function test_beel_has_lat_long(): void
    {
        $beel = Beel::whereNotNull('latitude')->first();
        $this->assertNotNull($beel);
        $this->assertNotNull($beel->longitude);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Beel;
use App\Models\Grievance;
use App\Models\GrievanceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPortalTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /** Submit a grievance with the mobile pre-verified in session (OTP gate). */
    private function submit(array $overrides = [])
    {
        $mobile = $overrides['mobile'] ?? '9876543210';

        $payload = array_merge([
            'mode_of_receipt' => 'online',
            'category_id' => GrievanceCategory::where('code', 2)->first()->id,
            'name' => 'Test Person',
            'gender' => 'Male',
            'mobile' => $mobile,
            'address' => 'Some address',
            'place_village' => 'Test Village',
            'beel_id' => Beel::first()->id,
            'description' => 'A test grievance description for the feature test.',
        ], $overrides);

        return $this->withSession(['otp_verified_'.$mobile => true])->post('/submit', $payload);
    }

    public function test_home_page_loads(): void
    {
        $this->get('/')->assertOk()->assertSee('Grievance Redressal Mechanism');
    }

    public function test_public_pages_load(): void
    {
        foreach (['/submit', '/track', '/grm-process', '/faq', '/resources', '/privacy-policy', '/contact', '/login'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_a_grievance_can_be_submitted_and_gets_a_tracking_id(): void
    {
        $this->submit();

        $grievance = Grievance::latest('id')->first();
        $this->assertStringStartsWith('GRM-', $grievance->tracking_id);
        $this->assertSame('registered', $grievance->status);
        $this->assertSame(1, (int) $grievance->current_level);
        $this->assertNotNull($grievance->due_at);
        $this->assertSame(2, $grievance->actions()->count());
    }

    public function test_beel_is_optional_on_registration(): void
    {
        // Phase 2: Beel is no longer mandatory.
        $this->submit(['beel_id' => null]);

        $grievance = Grievance::latest('id')->first();
        $this->assertNotNull($grievance);
        $this->assertNull($grievance->beel_id);
    }

    public function test_sensitive_category_flags_the_grievance(): void
    {
        $sensitive = GrievanceCategory::where('is_sensitive', true)->first();
        $this->submit(['category_id' => $sensitive->id]);

        $this->assertTrue((bool) Grievance::latest('id')->first()->is_sensitive);
    }

    public function test_invalid_mobile_is_rejected(): void
    {
        $this->submit(['mobile' => '12345'])->assertSessionHasErrors('mobile');
    }

    public function test_a_grievance_can_be_tracked(): void
    {
        $this->submit(['name' => 'Trackable']);
        $grievance = Grievance::latest('id')->first();

        $this->post('/track', ['query' => $grievance->tracking_id])
            ->assertOk()
            ->assertSee($grievance->tracking_id);
    }
}

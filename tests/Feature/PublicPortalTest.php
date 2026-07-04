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
        $category = GrievanceCategory::where('code', 2)->first();
        $beel = Beel::first();

        $response = $this->post('/submit', [
            'mode_of_receipt' => 'online',
            'category_id' => $category->id,
            'name' => 'Test Person',
            'gender' => 'Male',
            'mobile' => '9876543210',
            'address' => 'Some address',
            'place_village' => 'Test Village',
            'beel_id' => $beel->id,
            'description' => 'A test grievance description for the feature test.',
        ]);

        $grievance = Grievance::latest('id')->first();
        $response->assertRedirect(route('grievance.submitted', $grievance->tracking_id));

        $this->assertStringStartsWith('GRM-', $grievance->tracking_id);
        $this->assertSame('registered', $grievance->status);
        $this->assertSame(1, (int) $grievance->current_level);
        $this->assertNotNull($grievance->due_at);
        // Registered + acknowledged actions logged.
        $this->assertSame(2, $grievance->actions()->count());
    }

    public function test_sensitive_category_flags_the_grievance(): void
    {
        $sensitive = GrievanceCategory::where('is_sensitive', true)->first();
        $beel = Beel::first();

        $this->post('/submit', [
            'mode_of_receipt' => 'online',
            'category_id' => $sensitive->id,
            'name' => 'Reporter',
            'mobile' => '9876500000',
            'address' => 'Addr',
            'place_village' => 'Village',
            'beel_id' => $beel->id,
            'description' => 'Sensitive matter reported for testing.',
        ]);

        $this->assertTrue((bool) Grievance::latest('id')->first()->is_sensitive);
    }

    public function test_invalid_mobile_is_rejected(): void
    {
        $category = GrievanceCategory::first();
        $beel = Beel::first();

        $this->post('/submit', [
            'mode_of_receipt' => 'online',
            'category_id' => $category->id,
            'name' => 'Bad Mobile',
            'mobile' => '12345',
            'address' => 'Addr',
            'place_village' => 'Village',
            'beel_id' => $beel->id,
            'description' => 'Testing mobile validation.',
        ])->assertSessionHasErrors('mobile');
    }

    public function test_a_grievance_can_be_tracked(): void
    {
        $category = GrievanceCategory::first();
        $beel = Beel::first();
        $this->post('/submit', [
            'mode_of_receipt' => 'online',
            'category_id' => $category->id,
            'name' => 'Trackable',
            'mobile' => '9811111111',
            'address' => 'Addr',
            'place_village' => 'Village',
            'beel_id' => $beel->id,
            'description' => 'A trackable grievance.',
        ]);
        $grievance = Grievance::latest('id')->first();

        $this->post('/track', ['query' => $grievance->tracking_id])
            ->assertOk()
            ->assertSee($grievance->tracking_id);
    }
}

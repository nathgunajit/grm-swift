<?php

namespace Tests\Feature;

use App\Models\Beel;
use App\Models\Grievance;
use App\Models\GrievanceCategory;
use App\Models\User;
use App\Services\GrievanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GrievanceWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function makeGrievance(): Grievance
    {
        $service = app(GrievanceService::class);
        $beel = Beel::where('name', 'Deepor Beel')->first(); // Kamrup
        $tid = $service->generateTrackingId();

        return Grievance::create([
            'tracking_id' => $tid,
            'acknowledgment_no' => $service->generateAcknowledgmentNo($tid),
            'mode_of_receipt' => 'online',
            'category_id' => GrievanceCategory::first()->id,
            'name' => 'Workflow Test',
            'mobile' => '9800000000',
            'place_village' => 'V',
            'beel_id' => $beel->id,
            'district_id' => $beel->district_id,
            'description' => 'Workflow test grievance.',
            'status' => 'registered',
            'current_level' => 1,
            'due_at' => $service->dueDateForLevel(1),
        ]);
    }

    public function test_service_generates_sequential_tracking_ids(): void
    {
        $service = app(GrievanceService::class);
        $a = $service->generateTrackingId();
        $g = $this->makeGrievance();
        $b = $service->generateTrackingId();

        $this->assertMatchesRegularExpression('/^GRM-\d{4}-\d{6}$/', $a);
        $this->assertNotSame($a, $b);
    }

    public function test_escalation_increments_level_and_sets_new_sla(): void
    {
        $grievance = $this->makeGrievance();
        $service = app(GrievanceService::class);

        $service->escalate($grievance, null, 'test escalation');
        $grievance->refresh();

        $this->assertSame(2, (int) $grievance->current_level);
        $this->assertSame('escalated', $grievance->status);
        // Level II SLA is 15 days.
        $this->assertEqualsWithDelta(15, now()->diffInDays($grievance->due_at), 1);
        $this->assertDatabaseHas('grievance_actions', [
            'grievance_id' => $grievance->id,
            'action' => 'escalated',
            'from_level' => 1,
            'to_level' => 2,
        ]);
    }

    public function test_escalation_stops_at_level_three(): void
    {
        $grievance = $this->makeGrievance();
        $service = app(GrievanceService::class);

        $this->assertTrue($service->escalate($grievance));  // 1 -> 2
        $this->assertTrue($service->escalate($grievance));  // 2 -> 3
        $this->assertFalse($service->escalate($grievance)); // cannot go beyond 3
        $this->assertSame(3, (int) $grievance->fresh()->current_level);
    }

    public function test_resolve_sets_status_and_timestamp(): void
    {
        $grievance = $this->makeGrievance();
        app(GrievanceService::class)->resolve($grievance, 'Resolved in test.');
        $grievance->refresh();

        $this->assertSame('resolved', $grievance->status);
        $this->assertNotNull($grievance->resolved_at);
        $this->assertSame('Resolved in test.', $grievance->resolution);
    }

    public function test_officer_can_resolve_through_http(): void
    {
        $grievance = $this->makeGrievance();
        $ssgc = User::where('email', 'ssgc@grmswift.local')->first(); // Kamrup district

        $this->actingAs($ssgc)
            ->post(route('admin.grievances.resolve', $grievance), ['resolution' => 'Resolved via HTTP endpoint test.'])
            ->assertRedirect();

        $this->assertSame('resolved', $grievance->fresh()->status);
    }

    public function test_officer_cannot_view_grievance_outside_jurisdiction(): void
    {
        // A grievance in Barpeta; SSGC is scoped to Kamrup.
        $service = app(GrievanceService::class);
        $beel = Beel::where('name', 'Sone Beel')->first(); // Barpeta
        $tid = $service->generateTrackingId();
        $outside = Grievance::create([
            'tracking_id' => $tid,
            'acknowledgment_no' => $service->generateAcknowledgmentNo($tid),
            'mode_of_receipt' => 'online',
            'category_id' => GrievanceCategory::first()->id,
            'place_village' => 'V',
            'beel_id' => $beel->id,
            'district_id' => $beel->district_id,
            'description' => 'Outside jurisdiction.',
            'status' => 'registered',
            'current_level' => 1,
        ]);

        $ssgc = User::where('email', 'ssgc@grmswift.local')->first();
        $this->actingAs($ssgc)->get(route('admin.grievances.show', $outside))->assertForbidden();
    }
}

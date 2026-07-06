<?php

namespace Tests\Feature;

use App\Models\Beel;
use App\Models\Grievance;
use App\Models\GrievanceCategory;
use App\Models\User;
use App\Services\GrievanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleActionTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function kamrupGrievance(): Grievance
    {
        $service = app(GrievanceService::class);
        $beel = Beel::where('name', 'Deepor Beel')->first(); // Kamrup
        $tid = $service->generateTrackingId();

        return Grievance::create([
            'tracking_id' => $tid,
            'acknowledgment_no' => $service->generateAcknowledgmentNo($tid),
            'mode_of_receipt' => 'online',
            'category_id' => GrievanceCategory::first()->id,
            'name' => 'Role Test', 'mobile' => '9800000000',
            'place_village' => 'V', 'beel_id' => $beel->id, 'district_id' => $beel->district_id,
            'description' => 'Role-based action test grievance.',
            'status' => 'registered', 'current_level' => 1,
            'due_at' => $service->dueDateForLevel(1),
        ]);
    }

    public function test_beel_animator_cannot_resolve(): void
    {
        $g = $this->kamrupGrievance();
        $user = User::where('email', 'animator@grmswift.local')->first();

        $this->actingAs($user)
            ->post(route('admin.grievances.resolve', $g), ['resolution' => 'Trying to resolve as animator.'])
            ->assertForbidden();
        $this->assertNotSame('resolved', $g->fresh()->status);
    }

    public function test_beel_animator_can_escalate(): void
    {
        $g = $this->kamrupGrievance();
        $user = User::where('email', 'animator@grmswift.local')->first();

        $this->actingAs($user)
            ->post(route('admin.grievances.escalate', $g), ['to_level' => 2])
            ->assertRedirect();
        $this->assertSame(2, (int) $g->fresh()->current_level);
    }

    public function test_dfdo_can_resolve(): void
    {
        $g = $this->kamrupGrievance();
        $user = User::where('email', 'dfdo@grmswift.local')->first();

        $this->actingAs($user)
            ->post(route('admin.grievances.resolve', $g), ['resolution' => 'Resolved by DFDO at field level.'])
            ->assertRedirect();
        $this->assertSame('resolved', $g->fresh()->status);
    }

    public function test_pmu_admin_is_view_only(): void
    {
        $g = $this->kamrupGrievance();
        $user = User::where('email', 'pmu@grmswift.local')->first();

        // Can view (PMU sees all)…
        $this->actingAs($user)->get(route('admin.grievances.show', $g))->assertOk();
        // …but cannot act.
        $this->actingAs($user)->post(route('admin.grievances.comment', $g), ['remarks' => 'x'])->assertForbidden();
        $this->actingAs($user)->post(route('admin.grievances.resolve', $g), ['resolution' => 'nope'])->assertForbidden();
    }

    public function test_bdc_facilitator_cannot_open_manual_entry(): void
    {
        $user = User::where('email', 'bdc@grmswift.local')->first();
        $this->actingAs($user)->get(route('admin.grievances.create'))->assertForbidden();
    }

    public function test_beel_animator_can_open_manual_entry(): void
    {
        $user = User::where('email', 'animator@grmswift.local')->first();
        $this->actingAs($user)->get(route('admin.grievances.create'))->assertOk();
    }
}

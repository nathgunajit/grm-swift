<?php

namespace Database\Seeders;

use App\Models\Beel;
use App\Models\Grievance;
use App\Models\GrievanceCategory;
use App\Models\GrievanceFeedback;
use App\Services\GrievanceService;
use Illuminate\Database\Seeder;

class GrievanceSeeder extends Seeder
{
    public function run(): void
    {
        if (Grievance::count() > 0) {
            return;
        }

        $service = app(GrievanceService::class);
        $beels = Beel::all()->keyBy('name');
        $catBySlug = GrievanceCategory::all()->keyBy('code');

        $samples = [
            [
                'name' => 'Ramen Ali', 'gender' => 'Male', 'mobile' => '9812345670',
                'beel' => 'Deepor Beel', 'category' => 2, 'status' => 'registered', 'level' => 1,
                'description' => 'Payment for fish seed support not yet received after three months.',
            ],
            [
                'name' => 'Sabita Rabha', 'gender' => 'Female', 'mobile' => '9812345671',
                'beel' => 'Morikolong Beel', 'category' => 1, 'status' => 'under_review', 'level' => 1,
                'description' => 'Eligible household excluded from beneficiary list for the beel fishery.',
            ],
            [
                'name' => 'Anonymous', 'gender' => null, 'mobile' => null, 'anonymous' => true,
                'beel' => 'Sone Beel', 'category' => 6, 'status' => 'escalated', 'level' => 2,
                'description' => 'Alleged misconduct by a field service provider during survey.',
                'sensitive' => true, 'confidential' => true,
            ],
            [
                'name' => 'Dilip Boro', 'gender' => 'Male', 'mobile' => '9812345673',
                'beel' => 'Charan Beel', 'category' => 3, 'status' => 'resolved', 'level' => 2,
                'description' => 'Poor quality of embankment construction near the beel.',
                'resolution' => 'Site inspected by GRC-II; contractor directed to rectify the embankment. Work completed and verified.',
            ],
            [
                'name' => 'Jyoti Das', 'gender' => 'Female', 'mobile' => '9812345674',
                'beel' => 'Diplai Beel', 'category' => 4, 'status' => 'resolved', 'level' => 1,
                'description' => 'Water contamination affecting fish stock in the beel.',
                'resolution' => 'SSGC coordinated with environment coordinator; source of contamination addressed. Complainant informed.',
                'feedback' => true,
            ],
            [
                'name' => 'Hiren Nath', 'gender' => 'Male', 'mobile' => '9812345675',
                'beel' => 'Deepor Beel', 'category' => 8, 'status' => 'escalated', 'level' => 3,
                'description' => 'Suggestion to add more drop-boxes; escalated as not addressed at lower levels.',
            ],
        ];

        foreach ($samples as $s) {
            $trackingId = $service->generateTrackingId();
            $beel = $beels->get($s['beel']);
            $category = $catBySlug->get($s['category']);
            $level = $s['level'];

            $g = Grievance::create([
                'tracking_id' => $trackingId,
                'acknowledgment_no' => $service->generateAcknowledgmentNo($trackingId),
                'mode_of_receipt' => 'online',
                'category_id' => $category?->id,
                'name' => $s['anonymous'] ?? false ? null : $s['name'],
                'gender' => $s['gender'] ?? null,
                'mobile' => $s['mobile'] ?? null,
                'place_village' => $s['beel'].' area',
                'beel_id' => $beel?->id,
                'district_id' => $beel?->district_id,
                'description' => $s['description'],
                'is_anonymous' => $s['anonymous'] ?? false,
                'is_confidential' => $s['confidential'] ?? false,
                'is_sensitive' => ($s['sensitive'] ?? false) || ($category?->is_sensitive ?? false),
                'status' => $s['status'],
                'current_level' => $level,
                'due_at' => $service->dueDateForLevel($level),
                'resolution' => $s['resolution'] ?? null,
                'resolved_at' => in_array($s['status'], ['resolved', 'closed'], true) ? now()->subDays(1) : null,
            ]);

            // Timeline entries
            $service->logAction($g, 'registered', null, 'Grievance registered online.', null, 1);
            $service->logAction($g, 'acknowledged', null, 'Acknowledgment issued: '.$g->acknowledgment_no, 1, 1);
            if ($s['status'] === 'under_review') {
                $service->logAction($g, 'reviewed', null, 'Under review at field level.', 1, 1);
            }
            for ($l = 1; $l < $level; $l++) {
                $service->logAction($g, 'escalated', null, 'Escalated to next level.', $l, $l + 1);
            }
            if (! empty($s['resolution'])) {
                $service->logAction($g, 'resolved', null, $s['resolution'], $level, $level);
            }

            if (! empty($s['feedback'])) {
                GrievanceFeedback::create([
                    'grievance_id' => $g->id,
                    'informed' => true,
                    'heard_respectfully' => true,
                    'response_time_ok' => true,
                    'satisfaction' => 'fully',
                    'transparency' => 'good',
                    'official_behavior' => 'good',
                    'feel_safe' => true,
                    'rating' => 5,
                    'comments' => 'Prompt and fair resolution.',
                ]);
            }
        }
    }
}

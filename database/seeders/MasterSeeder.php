<?php

namespace Database\Seeders;

use App\Models\Beel;
use App\Models\Block;
use App\Models\Committee;
use App\Models\CommitteeMember;
use App\Models\Cpiu;
use App\Models\District;
use App\Models\GrievanceCategory;
use App\Models\RevenueCircle;
use App\Models\UserType;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        // --- User types / roles (from GRM manual) ---
        $roles = [
            ['name' => 'Public User', 'slug' => 'public', 'description' => 'Citizen / complainant. Submit and track grievances.'],
            ['name' => 'Beel Animator', 'slug' => 'beel_animator', 'description' => 'Registers and tracks grievances at beel level, manual entry.'],
            ['name' => 'BDC Facilitator', 'slug' => 'bdc_facilitator', 'description' => 'Reviews grievances and escalates to SSGC.'],
            ['name' => 'SSGC', 'slug' => 'ssgc', 'description' => 'Social Safeguards & Gender Coordinator. Monitors and coordinates Level-I/II.'],
            ['name' => 'DFDO', 'slug' => 'dfdo', 'description' => 'District Fisheries Development Officer. Field-level authority (Level I).'],
            ['name' => 'CPIU Officer', 'slug' => 'cpiu_officer', 'description' => 'Cluster / CPIU level resolution (Level II).'],
            ['name' => 'PIU Officer', 'slug' => 'piu_officer', 'description' => 'Final-level resolution (Level III).'],
            ['name' => 'PMU Admin', 'slug' => 'pmu_admin', 'description' => 'System monitoring across all levels.'],
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Full system control.'],
        ];
        foreach ($roles as $r) {
            UserType::updateOrCreate(['slug' => $r['slug']], $r);
        }

        // --- Grievance categories (codes 1-9 from Annexure II) ---
        $categories = [
            ['code' => 1, 'name' => 'Beneficiary selection', 'is_sensitive' => false],
            ['code' => 2, 'name' => 'Benefit / payment issue', 'is_sensitive' => false],
            ['code' => 3, 'name' => 'Construction / work quality', 'is_sensitive' => false],
            ['code' => 4, 'name' => 'Environmental impact', 'is_sensitive' => false],
            ['code' => 5, 'name' => 'Social impact', 'is_sensitive' => false],
            ['code' => 6, 'name' => 'Staff behaviour / misconduct', 'is_sensitive' => true],
            ['code' => 7, 'name' => 'Inclusion / exclusion', 'is_sensitive' => false],
            ['code' => 8, 'name' => 'Suggestion / feedback', 'is_sensitive' => false],
            ['code' => 9, 'name' => 'Other (incl. GBV / SEA-SH, corruption)', 'is_sensitive' => true],
        ];
        foreach ($categories as $c) {
            GrievanceCategory::updateOrCreate(['code' => $c['code']], $c);
        }

        // --- Sample Assam districts, CPIUs, blocks, beels ---
        // CPIUs
        $cpiuNames = ['CPIU Guwahati', 'CPIU Nagaon', 'CPIU Barpeta'];
        $cpius = [];
        foreach ($cpiuNames as $i => $name) {
            $cpius[$name] = Cpiu::updateOrCreate(
                ['name' => $name],
                ['code' => 'C'.str_pad($i + 1, 2, '0', STR_PAD_LEFT)]
            );
        }

        // Districts (each belongs to one CPIU)
        $districtData = [
            'Kamrup' => 'CPIU Guwahati',
            'Nagaon' => 'CPIU Nagaon',
            'Barpeta' => 'CPIU Barpeta',
            'Morigaon' => 'CPIU Nagaon',
            'Dhubri' => 'CPIU Barpeta',
        ];
        $districts = [];
        $i = 0;
        foreach ($districtData as $name => $cpiuName) {
            $districts[$name] = District::updateOrCreate(
                ['name' => $name],
                ['code' => 'D'.str_pad(++$i, 2, '0', STR_PAD_LEFT), 'cpiu_id' => $cpius[$cpiuName]->id]
            );
        }

        foreach ($districts as $name => $district) {
            Block::updateOrCreate(['district_id' => $district->id, 'name' => $name.' Block']);
            RevenueCircle::updateOrCreate(['district_id' => $district->id, 'name' => $name.' Revenue Circle']);
        }

        $beelData = [
            ['name' => 'Deepor Beel', 'district' => 'Kamrup', 'cpiu' => 'CPIU Guwahati', 'lat' => 26.1197, 'lng' => 91.6533],
            ['name' => 'Sone Beel', 'district' => 'Barpeta', 'cpiu' => 'CPIU Barpeta', 'lat' => 24.5333, 'lng' => 92.4667],
            ['name' => 'Morikolong Beel', 'district' => 'Nagaon', 'cpiu' => 'CPIU Nagaon', 'lat' => 26.3500, 'lng' => 92.6800],
            ['name' => 'Charan Beel', 'district' => 'Morigaon', 'cpiu' => 'CPIU Nagaon', 'lat' => 26.2500, 'lng' => 92.3400],
            ['name' => 'Diplai Beel', 'district' => 'Dhubri', 'cpiu' => 'CPIU Barpeta', 'lat' => 26.0200, 'lng' => 89.9800],
        ];
        foreach ($beelData as $b) {
            $district = $districts[$b['district']];
            $block = Block::where('district_id', $district->id)->first();
            Beel::updateOrCreate(
                ['name' => $b['name']],
                [
                    'district_id' => $district->id,
                    'block_id' => $block?->id,
                    'cpiu_id' => $cpius[$b['cpiu']]->id,
                    'latitude' => $b['lat'],
                    'longitude' => $b['lng'],
                ]
            );
        }

        // --- Sample committees (one per level) with members (>=30% women) ---
        if (Committee::count() === 0) {
            $c1 = Committee::create(['name' => 'Kamrup Field GRC', 'level' => 1, 'district_id' => $districts['Kamrup']->id, 'cpiu_id' => $cpius['CPIU Guwahati']->id]);
            foreach ([
                ['DFDO / SWIFT Nodal Officer', 'chairperson', false],
                ['BDC Facilitator (NGO)', 'convenor', false],
                ['Social Safeguards & Gender Coordinator', 'member', true],
                ['Fisher Cooperative / SHG Representative', 'member', true],
                ['Beel Animator', 'rapporteur', false],
            ] as [$n, $role, $woman]) {
                CommitteeMember::create(['committee_id' => $c1->id, 'name' => $n, 'designation' => $n, 'role' => $role, 'is_woman' => $woman]);
            }

            $c2 = Committee::create(['name' => 'CPIU Guwahati Cluster GRC', 'level' => 2, 'cpiu_id' => $cpius['CPIU Guwahati']->id]);
            foreach ([
                ['Zonal Officer, CPIU', 'chairperson', false],
                ['DFDO', 'convenor', false],
                ['ST Community Representative (woman)', 'member', true],
                ['Social Safeguards / Environment Coordinator', 'rapporteur', true],
            ] as [$n, $role, $woman]) {
                CommitteeMember::create(['committee_id' => $c2->id, 'name' => $n, 'designation' => $n, 'role' => $role, 'is_woman' => $woman]);
            }

            $c3 = Committee::create(['name' => 'PIU Level GRC', 'level' => 3]);
            foreach ([
                ['Deputy Project Director, SWIFT', 'chairperson', false],
                ['Social Safeguards & Gender Specialist', 'convenor', true],
                ['Senior Project Advisor (PMU)', 'member', false],
                ['Communication Specialist', 'rapporteur', true],
            ] as [$n, $role, $woman]) {
                CommitteeMember::create(['committee_id' => $c3->id, 'name' => $n, 'designation' => $n, 'role' => $role, 'is_woman' => $woman]);
            }
        }
    }
}

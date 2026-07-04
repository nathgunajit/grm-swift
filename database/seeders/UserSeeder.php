<?php

namespace Database\Seeders;

use App\Models\Beel;
use App\Models\Cpiu;
use App\Models\District;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $types = UserType::pluck('id', 'slug');
        $kamrup = District::where('name', 'Kamrup')->first();
        $cpiu = Cpiu::where('name', 'CPIU Guwahati')->first();
        $beel = Beel::where('name', 'Deepor Beel')->first();

        // Super Admin
        User::updateOrCreate(
            ['email' => 'admin@grmswift.local'],
            [
                'empid' => 'EMP-ADMIN',
                'name' => 'Super Admin',
                'mobile' => '9000000000',
                'password' => Hash::make('Admin@123'),
                'designation' => 'System Administrator',
                'user_type_id' => $types['super_admin'],
                'is_active' => true,
            ]
        );

        // One demo user per operational role (password: Password@123)
        $roleUsers = [
            'beel_animator' => ['Bikash Das', 'animator@grmswift.local', '9000000001', true],
            'bdc_facilitator' => ['Rekha Bora', 'bdc@grmswift.local', '9000000002', true],
            'ssgc' => ['Anjali Deka', 'ssgc@grmswift.local', '9000000003', true],
            'dfdo' => ['Pranab Kalita', 'dfdo@grmswift.local', '9000000004', true],
            'cpiu_officer' => ['Sanjib Nath', 'cpiu@grmswift.local', '9000000005', true],
            'piu_officer' => ['Manash Sarma', 'piu@grmswift.local', '9000000006', false],
            'pmu_admin' => ['Nabajit Roy', 'pmu@grmswift.local', '9000000007', false],
        ];

        foreach ($roleUsers as $slug => [$name, $email, $mobile, $withBeel]) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'empid' => 'EMP-'.strtoupper($slug),
                    'name' => $name,
                    'mobile' => $mobile,
                    'password' => Hash::make('Password@123'),
                    'designation' => UserType::where('slug', $slug)->value('name'),
                    'user_type_id' => $types[$slug],
                    'district_id' => $kamrup?->id,
                    'cpiu_id' => $cpiu?->id,
                    'beel_id' => $withBeel ? $beel?->id : null,
                    'is_active' => true,
                ]
            );
        }
    }
}

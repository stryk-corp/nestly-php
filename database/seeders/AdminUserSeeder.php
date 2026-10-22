<?php

namespace Database\Seeders;

use App\Models\Hostel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Creates default seed accounts for first-run setup & testing:
 *   - Administrator: admin@nestly.local / Admin@12345
 *   - Porter:        porter@nestly.local / Porter@12345
 *   - Student:       student@nestly.local / Student@12345
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $firstHostel = Hostel::where('is_active', true)->first();
        $hostelId = $firstHostel?->id;

        // 1. Administrator
        User::firstOrCreate(
            ['email' => 'admin@nestly.local'],
            [
                'first_name'  => 'System',
                'last_name'   => 'Administrator',
                'password'    => Hash::make('Admin@12345'),
                'role'        => User::ROLE_ADMIN,
                'phone'       => '08000000001',
                'is_active'   => true,
                'is_approved' => true,
            ]
        );

        // 2. Porter
        User::firstOrCreate(
            ['email' => 'porter@nestly.local'],
            [
                'first_name'  => 'Chief',
                'last_name'   => 'Porter',
                'password'    => Hash::make('Porter@12345'),
                'role'        => User::ROLE_PORTER,
                'hostel_id'   => $hostelId,
                'phone'       => '08000000002',
                'is_active'   => true,
                'is_approved' => true,
            ]
        );

        // 3. Student
        User::firstOrCreate(
            ['email' => 'student@nestly.local'],
            [
                'first_name'  => 'Demo',
                'last_name'   => 'Student',
                'password'    => Hash::make('Student@12345'),
                'role'        => User::ROLE_STUDENT,
                'hostel_id'   => $hostelId,
                'phone'       => '08000000003',
                'is_active'   => true,
                'is_approved' => true,
            ]
        );

        $this->command->info('Seeded accounts created:');
        $this->command->info('  - Admin:   admin@nestly.local / Admin@12345');
        $this->command->info('  - Porter:  porter@nestly.local / Porter@12345');
        $this->command->info('  - Student: student@nestly.local / Student@12345');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('slug', 'admin')->first();
        $educatorRole = Role::where('slug', 'educator')->first();
        $studentRole = Role::where('slug', 'student')->first();

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@talinohub.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'address' => '123 Admin Street, Manila, Philippines',
                'email_verified_at' => now(),
            ]
        );

        if ($adminRole) {
            $admin->assignRole($adminRole);
        }

        $this->command->info('✓ Admin user created: admin@talinohub.com');

        // Create Educator Users
        $educators = [
            [
                'name' => 'Dr. Maria Santos',
                'email' => 'maria.santos@talinohub.com',
                'password' => Hash::make('password'),
                'address' => '456 Educator Ave, Quezon City, Philippines',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Prof. Juan dela Cruz',
                'email' => 'juan.delacruz@talinohub.com',
                'password' => Hash::make('password'),
                'address' => '789 Teacher Road, Makati, Philippines',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Engr. Ana Reyes',
                'email' => 'ana.reyes@talinohub.com',
                'password' => Hash::make('password'),
                'address' => '321 Learning Street, Pasig, Philippines',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($educators as $educatorData) {
            $educator = User::firstOrCreate(
                ['email' => $educatorData['email']],
                $educatorData
            );

            if ($educatorRole) {
                $educator->assignRole($educatorRole);
            }

            $this->command->info('✓ Educator created: ' . $educatorData['email']);
        }

        // Create Student Users
        $students = [
            [
                'name' => 'Carlos Martinez',
                'email' => 'carlos.martinez@student.com',
                'password' => Hash::make('password'),
                'address' => '111 Student Lane, Taguig, Philippines',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Isabel Garcia',
                'email' => 'isabel.garcia@student.com',
                'password' => Hash::make('password'),
                'address' => '222 Learner Street, Pasay, Philippines',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Miguel Rodriguez',
                'email' => 'miguel.rodriguez@student.com',
                'password' => Hash::make('password'),
                'address' => '333 Scholar Road, Mandaluyong, Philippines',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sofia Fernandez',
                'email' => 'sofia.fernandez@student.com',
                'password' => Hash::make('password'),
                'address' => '444 Academy Ave, San Juan, Philippines',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Diego Lopez',
                'email' => 'diego.lopez@student.com',
                'password' => Hash::make('password'),
                'address' => '555 Education Blvd, Muntinlupa, Philippines',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($students as $studentData) {
            $student = User::firstOrCreate(
                ['email' => $studentData['email']],
                $studentData
            );

            if ($studentRole) {
                $student->assignRole($studentRole);
            }

            $this->command->info('✓ Student created: ' . $studentData['email']);
        }

        $this->command->info('');
        $this->command->info('===========================================');
        $this->command->info('Users seeded successfully!');
        $this->command->info('===========================================');
        $this->command->info('Admin Account:');
        $this->command->info('  Email: admin@talinohub.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('Educator Accounts:');
        $this->command->info('  - maria.santos@talinohub.com (password)');
        $this->command->info('  - juan.delacruz@talinohub.com (password)');
        $this->command->info('  - ana.reyes@talinohub.com (password)');
        $this->command->info('');
        $this->command->info('Student Accounts:');
        $this->command->info('  - carlos.martinez@student.com (password)');
        $this->command->info('  - isabel.garcia@student.com (password)');
        $this->command->info('  - miguel.rodriguez@student.com (password)');
        $this->command->info('  - sofia.fernandez@student.com (password)');
        $this->command->info('  - diego.lopez@student.com (password)');
        $this->command->info('===========================================');
    }
}

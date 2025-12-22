<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🌱 Starting TalinoHub Database Seeding...');
        $this->command->info('==========================================');
        $this->command->info('');

        // Seed in specific order due to dependencies
        $this->call([
            RolesAndPermissionsSeeder::class,
            UsersSeeder::class,
            CoursesSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('==========================================');
        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->info('==========================================');
        $this->command->info('');
        $this->command->info('📖 Quick Start Guide:');
        $this->command->info('');
        $this->command->info('1. Admin Portal:');
        $this->command->info('   - Email: admin@talinohub.com');
        $this->command->info('   - Password: password');
        $this->command->info('   - Access: Full system administration');
        $this->command->info('');
        $this->command->info('2. Educator Accounts (Create & Manage Courses):');
        $this->command->info('   - maria.santos@talinohub.com');
        $this->command->info('   - juan.delacruz@talinohub.com');
        $this->command->info('   - ana.reyes@talinohub.com');
        $this->command->info('   - Password: password (for all)');
        $this->command->info('');
        $this->command->info('3. Student Accounts (Enroll in Courses):');
        $this->command->info('   - carlos.martinez@student.com');
        $this->command->info('   - isabel.garcia@student.com');
        $this->command->info('   - miguel.rodriguez@student.com');
        $this->command->info('   - sofia.fernandez@student.com');
        $this->command->info('   - diego.lopez@student.com');
        $this->command->info('   - Password: password (for all)');
        $this->command->info('');
        $this->command->info('📚 Sample Courses Created:');
        $this->command->info('   - 7 Published Courses across different categories');
        $this->command->info('   - 1 Draft Course (Coming Soon)');
        $this->command->info('   - Complete with lessons, ranging from 5-10 lessons per course');
        $this->command->info('   - Mix of free and paid courses');
        $this->command->info('');
        $this->command->info('Happy Learning! 🚀');
        $this->command->info('==========================================');
    }
}

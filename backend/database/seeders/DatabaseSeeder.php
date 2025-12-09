<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseComment;
use App\Models\CourseRating;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting to seed database...');

        // Create Users
        $this->command->info('👥 Creating users...');

        // Create Educators
        $educator1 = User::create([
            'name' => 'Dr. Sarah Johnson',
            'email' => 'sarah.johnson@example.com',
            'password' => Hash::make('password123'),
            'address' => '123 Education St, New York, NY',
        ]);

        $educator2 = User::create([
            'name' => 'Prof. Michael Chen',
            'email' => 'michael.chen@example.com',
            'password' => Hash::make('password123'),
            'address' => '456 Learning Ave, San Francisco, CA',
        ]);

        $educator3 = User::create([
            'name' => 'Emma Martinez',
            'email' => 'emma.martinez@example.com',
            'password' => Hash::make('password123'),
            'address' => '789 Knowledge Blvd, Austin, TX',
        ]);

        // Create Students
        $students = [];
        $studentNames = [
            ['name' => 'John Smith', 'email' => 'john.smith@example.com'],
            ['name' => 'Alice Brown', 'email' => 'alice.brown@example.com'],
            ['name' => 'David Wilson', 'email' => 'david.wilson@example.com'],
            ['name' => 'Sophie Taylor', 'email' => 'sophie.taylor@example.com'],
            ['name' => 'James Anderson', 'email' => 'james.anderson@example.com'],
            ['name' => 'Olivia Davis', 'email' => 'olivia.davis@example.com'],
            ['name' => 'Lucas Miller', 'email' => 'lucas.miller@example.com'],
            ['name' => 'Mia Garcia', 'email' => 'mia.garcia@example.com'],
        ];

        foreach ($studentNames as $student) {
            $students[] = User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('password123'),
                'address' => 'Student Address',
            ]);
        }

        $this->command->info('✅ Created ' . (3 + count($students)) . ' users');

        // Create Courses
        $this->command->info('📚 Creating courses...');

        $course1 = Course::create([
            'user_id' => $educator1->id,
            'title' => 'Complete Web Development Bootcamp 2025',
            'slug' => 'complete-web-development-bootcamp-2025',
            'short_description' => 'Learn HTML, CSS, JavaScript, React, Node.js and more!',
            'description' => 'Master web development from scratch with this comprehensive bootcamp. Build real-world projects and land your dream job as a web developer.',
            'is_free' => false,
            'price' => 99.99,
            'category' => 'Web Development',
            'level' => 'Beginner',
            'language' => 'English',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $course2 = Course::create([
            'user_id' => $educator2->id,
            'title' => 'Python Programming for Beginners',
            'slug' => 'python-programming-for-beginners',
            'short_description' => 'Start your programming journey with Python',
            'description' => 'Learn Python from basics to advanced concepts. Perfect for absolute beginners with no prior programming experience.',
            'is_free' => true,
            'price' => 0,
            'category' => 'Programming',
            'level' => 'Beginner',
            'language' => 'English',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $course3 = Course::create([
            'user_id' => $educator1->id,
            'title' => 'Advanced React & Redux Masterclass',
            'slug' => 'advanced-react-redux-masterclass',
            'short_description' => 'Master React hooks, Redux, and modern state management',
            'description' => 'Deep dive into React ecosystem with Redux, Context API, custom hooks, and advanced patterns.',
            'is_free' => false,
            'price' => 79.99,
            'category' => 'Web Development',
            'level' => 'Advanced',
            'language' => 'English',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $course4 = Course::create([
            'user_id' => $educator3->id,
            'title' => 'Digital Marketing Fundamentals',
            'slug' => 'digital-marketing-fundamentals',
            'short_description' => 'Learn SEO, Social Media, Email Marketing & Analytics',
            'description' => 'Complete guide to digital marketing. Learn proven strategies to grow your business online.',
            'is_free' => true,
            'price' => 0,
            'category' => 'Marketing',
            'level' => 'Beginner',
            'language' => 'English',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $course5 = Course::create([
            'user_id' => $educator2->id,
            'title' => 'Machine Learning A-Z',
            'slug' => 'machine-learning-a-z',
            'short_description' => 'Master Machine Learning algorithms and build AI models',
            'description' => 'Comprehensive guide to Machine Learning. Build real-world ML models with Python and TensorFlow.',
            'is_free' => false,
            'price' => 129.99,
            'category' => 'Data Science',
            'level' => 'Intermediate',
            'language' => 'English',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->command->info('✅ Created 5 courses');

        // Create Lessons
        $this->command->info('📝 Creating lessons...');

        // Course 1 Lessons
        $course1Lessons = [
            ['title' => 'Introduction to Web Development', 'is_preview' => true, 'duration' => 600],
            ['title' => 'HTML Basics', 'is_preview' => true, 'duration' => 900],
            ['title' => 'CSS Fundamentals', 'is_preview' => false, 'duration' => 1200],
            ['title' => 'JavaScript Essentials', 'is_preview' => false, 'duration' => 1500],
            ['title' => 'Building Your First Website', 'is_preview' => false, 'duration' => 1800],
            ['title' => 'React Introduction', 'is_preview' => false, 'duration' => 1400],
            ['title' => 'Node.js & Express', 'is_preview' => false, 'duration' => 1600],
        ];

        foreach ($course1Lessons as $index => $lessonData) {
            Lesson::create([
                'course_id' => $course1->id,
                'title' => $lessonData['title'],
                'position' => $index + 1,
                'type' => 'video',
                'video_url' => 'lessons/video-' . ($index + 1) . '.mp4',
                'duration_seconds' => $lessonData['duration'],
                'is_preview' => $lessonData['is_preview'],
            ]);
        }

        // Course 2 Lessons
        $course2Lessons = [
            ['title' => 'Python Installation & Setup', 'is_preview' => true, 'duration' => 480],
            ['title' => 'Variables and Data Types', 'is_preview' => true, 'duration' => 720],
            ['title' => 'Control Flow & Loops', 'is_preview' => false, 'duration' => 900],
            ['title' => 'Functions in Python', 'is_preview' => false, 'duration' => 1000],
            ['title' => 'Object-Oriented Programming', 'is_preview' => false, 'duration' => 1200],
        ];

        foreach ($course2Lessons as $index => $lessonData) {
            Lesson::create([
                'course_id' => $course2->id,
                'title' => $lessonData['title'],
                'position' => $index + 1,
                'type' => 'video',
                'video_url' => 'lessons/course2-video-' . ($index + 1) . '.mp4',
                'duration_seconds' => $lessonData['duration'],
                'is_preview' => $lessonData['is_preview'],
            ]);
        }

        // Course 3 Lessons
        $course3Lessons = [
            ['title' => 'React Hooks Deep Dive', 'is_preview' => true, 'duration' => 1800],
            ['title' => 'Redux Fundamentals', 'is_preview' => false, 'duration' => 2000],
            ['title' => 'Redux Toolkit', 'is_preview' => false, 'duration' => 1600],
            ['title' => 'Advanced Patterns', 'is_preview' => false, 'duration' => 1900],
        ];

        foreach ($course3Lessons as $index => $lessonData) {
            Lesson::create([
                'course_id' => $course3->id,
                'title' => $lessonData['title'],
                'position' => $index + 1,
                'type' => 'video',
                'video_url' => 'lessons/course3-video-' . ($index + 1) . '.mp4',
                'duration_seconds' => $lessonData['duration'],
                'is_preview' => $lessonData['is_preview'],
            ]);
        }

        $this->command->info('✅ Created lessons for all courses');

        // Create Enrollments
        $this->command->info('🎓 Creating enrollments...');

        // Student enrollments with progress
        $course1->students()->attach($students[0]->id, [
            'enrolled_at' => now()->subDays(10),
            'progress' => 75,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $course1->students()->attach($students[1]->id, [
            'enrolled_at' => now()->subDays(5),
            'progress' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $course2->students()->attach($students[0]->id, [
            'enrolled_at' => now()->subDays(15),
            'progress' => 100,
            'completed_at' => now()->subDays(2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $course2->students()->attach($students[2]->id, [
            'enrolled_at' => now()->subDays(7),
            'progress' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $course3->students()->attach($students[1]->id, [
            'enrolled_at' => now()->subDays(3),
            'progress' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $course4->students()->attach($students[3]->id, [
            'enrolled_at' => now()->subDays(12),
            'progress' => 80,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $course5->students()->attach($students[4]->id, [
            'enrolled_at' => now()->subDays(20),
            'progress' => 100,
            'completed_at' => now()->subDays(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ Created 7 enrollments');

        // Create Favorites
        $this->command->info('⭐ Creating favorites...');

        $students[0]->favoriteCourses()->attach([$course1->id, $course3->id, $course5->id]);
        $students[1]->favoriteCourses()->attach([$course2->id, $course4->id]);
        $students[2]->favoriteCourses()->attach([$course1->id, $course2->id]);
        $students[3]->favoriteCourses()->attach([$course4->id]);
        $students[5]->favoriteCourses()->attach([$course1->id, $course5->id]);

        $this->command->info('✅ Created favorites');

        // Create Ratings
        $this->command->info('⭐ Creating ratings...');

        $ratings = [
            // Course 1 ratings
            ['user' => $students[0], 'course' => $course1, 'rating' => 5, 'review' => 'Amazing course! Best web development course I\'ve ever taken. The instructor explains everything clearly.'],
            ['user' => $students[1], 'course' => $course1, 'rating' => 4, 'review' => 'Great content, very comprehensive. Would recommend to beginners.'],
            ['user' => $students[2], 'course' => $course1, 'rating' => 5, 'review' => 'Excellent! Learned so much in just a few weeks.'],

            // Course 2 ratings
            ['user' => $students[0], 'course' => $course2, 'rating' => 5, 'review' => 'Perfect for Python beginners. Clear explanations and great examples.'],
            ['user' => $students[2], 'course' => $course2, 'rating' => 5, 'review' => 'Best free Python course available! Thank you for making this free.'],
            ['user' => $students[3], 'course' => $course2, 'rating' => 4, 'review' => 'Good course, well structured. Could use more advanced topics.'],

            // Course 3 ratings
            ['user' => $students[1], 'course' => $course3, 'rating' => 5, 'review' => 'Deep dive into React! Exactly what I was looking for.'],
            ['user' => $students[4], 'course' => $course3, 'rating' => 4, 'review' => 'Great advanced content. Assumes good knowledge of React basics.'],

            // Course 4 ratings
            ['user' => $students[3], 'course' => $course4, 'rating' => 5, 'review' => 'Excellent marketing fundamentals! Very practical and actionable.'],
            ['user' => $students[5], 'course' => $course4, 'rating' => 4, 'review' => 'Good introduction to digital marketing. Covers all the basics.'],

            // Course 5 ratings
            ['user' => $students[4], 'course' => $course5, 'rating' => 5, 'review' => 'Mind-blowing! ML concepts explained in a simple way. Worth every penny.'],
            ['user' => $students[0], 'course' => $course5, 'rating' => 5, 'review' => 'Best ML course for beginners. Hands-on projects are amazing!'],
        ];

        foreach ($ratings as $ratingData) {
            CourseRating::create([
                'user_id' => $ratingData['user']->id,
                'course_id' => $ratingData['course']->id,
                'rating' => $ratingData['rating'],
                'review' => $ratingData['review'],
            ]);

            // Update course rating stats
            $course = $ratingData['course'];
            $stats = CourseRating::where('course_id', $course->id)
                ->selectRaw('AVG(rating) as average, COUNT(*) as count')
                ->first();

            $course->update([
                'average_rating' => round($stats->average, 2),
                'ratings_count' => $stats->count,
            ]);
        }

        $this->command->info('✅ Created ' . count($ratings) . ' ratings');

        // Create Comments
        $this->command->info('💬 Creating comments...');

        // Course 1 comments
        $comment1 = CourseComment::create([
            'user_id' => $students[0]->id,
            'course_id' => $course1->id,
            'comment' => 'This is fantastic! Just finished the HTML section. Can\'t wait to continue!',
        ]);

        $comment2 = CourseComment::create([
            'user_id' => $students[1]->id,
            'course_id' => $course1->id,
            'comment' => 'I have a question about the CSS flexbox lesson. How do I center items both horizontally and vertically?',
        ]);

        $reply1 = CourseComment::create([
            'user_id' => $educator1->id,
            'course_id' => $course1->id,
            'parent_id' => $comment2->id,
            'comment' => 'Great question! Use `display: flex; justify-content: center; align-items: center;` on the parent container.',
        ]);

        $reply2 = CourseComment::create([
            'user_id' => $students[1]->id,
            'course_id' => $course1->id,
            'parent_id' => $comment2->id,
            'comment' => 'Thank you so much! That worked perfectly!',
        ]);

        // Course 2 comments
        $comment3 = CourseComment::create([
            'user_id' => $students[2]->id,
            'course_id' => $course2->id,
            'comment' => 'Best Python course ever! The explanations are so clear.',
        ]);

        $comment4 = CourseComment::create([
            'user_id' => $students[0]->id,
            'course_id' => $course2->id,
            'comment' => 'Just completed this course. Feeling confident to start my first Python project!',
        ]);

        $reply3 = CourseComment::create([
            'user_id' => $students[3]->id,
            'course_id' => $course2->id,
            'parent_id' => $comment4->id,
            'comment' => 'Congratulations! What project are you planning to build?',
        ]);

        // Course 3 comments
        $comment5 = CourseComment::create([
            'user_id' => $students[1]->id,
            'course_id' => $course3->id,
            'comment' => 'The Redux Toolkit section is brilliant! So much cleaner than traditional Redux.',
        ]);

        // Course 4 comments
        $comment6 = CourseComment::create([
            'user_id' => $students[3]->id,
            'course_id' => $course4->id,
            'comment' => 'Really enjoying this course! The SEO section was eye-opening.',
        ]);

        // Course 5 comments
        $comment7 = CourseComment::create([
            'user_id' => $students[4]->id,
            'course_id' => $course5->id,
            'comment' => 'Wow! Machine Learning is not as scary as I thought. Great teaching!',
        ]);

        $this->command->info('✅ Created 10 comments with nested replies');

        // Add Likes to Comments
        $this->command->info('👍 Creating comment likes...');

        // Add likes to various comments
        $comment1->likes()->attach([
            $students[1]->id,
            $students[2]->id,
            $students[3]->id,
            $students[4]->id,
        ]);
        $comment1->update(['likes_count' => 4]);

        $comment2->likes()->attach([
            $students[0]->id,
            $students[3]->id,
        ]);
        $comment2->update(['likes_count' => 2]);

        $reply1->likes()->attach([
            $students[0]->id,
            $students[1]->id,
            $students[2]->id,
        ]);
        $reply1->update(['likes_count' => 3]);

        $comment3->likes()->attach([
            $students[0]->id,
            $students[1]->id,
            $students[3]->id,
            $students[4]->id,
            $students[5]->id,
        ]);
        $comment3->update(['likes_count' => 5]);

        $comment4->likes()->attach([
            $students[2]->id,
            $students[3]->id,
        ]);
        $comment4->update(['likes_count' => 2]);

        $comment5->likes()->attach([
            $students[0]->id,
            $students[4]->id,
        ]);
        $comment5->update(['likes_count' => 2]);

        $comment7->likes()->attach([
            $students[0]->id,
            $students[1]->id,
            $students[2]->id,
            $students[5]->id,
        ]);
        $comment7->update(['likes_count' => 4]);

        $this->command->info('✅ Created comment likes');

        $this->command->info('');
        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Users: ' . User::count());
        $this->command->info('   - Courses: ' . Course::count());
        $this->command->info('   - Lessons: ' . Lesson::count());
        $this->command->info('   - Enrollments: ' . \DB::table('course_user')->count());
        $this->command->info('   - Ratings: ' . CourseRating::count());
        $this->command->info('   - Comments: ' . CourseComment::count());
        $this->command->info('');
        $this->command->info('🔐 Login Credentials:');
        $this->command->info('   Educators:');
        $this->command->info('   - sarah.johnson@example.com / password123');
        $this->command->info('   - michael.chen@example.com / password123');
        $this->command->info('   - emma.martinez@example.com / password123');
        $this->command->info('');
        $this->command->info('   Students:');
        $this->command->info('   - john.smith@example.com / password123');
        $this->command->info('   - alice.brown@example.com / password123');
        $this->command->info('   - david.wilson@example.com / password123');
        $this->command->info('   (and 5 more students with password123)');
    }
}

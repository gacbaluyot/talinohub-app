<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get educators
        $mariaSantos = User::where('email', 'maria.santos@talinohub.com')->first();
        $juanDelaCruz = User::where('email', 'juan.delacruz@talinohub.com')->first();
        $anaReyes = User::where('email', 'ana.reyes@talinohub.com')->first();

        if (!$mariaSantos || !$juanDelaCruz || !$anaReyes) {
            $this->command->error('Educators not found. Please run UsersSeeder first.');
            return;
        }

        // Courses for Maria Santos (Web Development)
        $webDevCourses = [
            [
                'title' => 'Introduction to Web Development',
                'short_description' => 'Learn the fundamentals of web development with HTML, CSS, and JavaScript',
                'description' => "This comprehensive course covers the basics of web development. You'll learn HTML for structure, CSS for styling, and JavaScript for interactivity. Perfect for beginners who want to start their journey in web development.\n\nWhat you'll learn:\n- HTML5 fundamentals and semantic markup\n- CSS3 styling and layouts\n- JavaScript basics and DOM manipulation\n- Responsive web design principles\n- Building your first website from scratch",
                'is_free' => true,
                'category' => 'Web Development',
                'level' => 'beginner',
                'language' => 'English',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Advanced JavaScript and Modern ES6+',
                'short_description' => 'Master modern JavaScript features and best practices',
                'description' => "Take your JavaScript skills to the next level with ES6+ features. Learn about arrow functions, destructuring, promises, async/await, and more.\n\nCourse highlights:\n- ES6+ syntax and features\n- Asynchronous JavaScript (Promises, Async/Await)\n- Modern JavaScript patterns\n- Working with APIs\n- Module systems and bundlers",
                'is_free' => false,
                'price' => 49.99,
                'category' => 'Web Development',
                'level' => 'intermediate',
                'language' => 'English',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Vue.js 3 Complete Course',
                'short_description' => 'Build modern web applications with Vue.js 3 and the Composition API',
                'description' => "Learn Vue.js 3 from scratch and build real-world applications. This course covers the Composition API, reactive data, components, routing, and state management with Pinia.\n\nYou will learn:\n- Vue 3 fundamentals and Composition API\n- Component-based architecture\n- Vue Router for navigation\n- Pinia for state management\n- Building and deploying Vue apps",
                'is_free' => false,
                'price' => 79.99,
                'category' => 'Web Development',
                'level' => 'intermediate',
                'language' => 'English',
                'is_published' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($webDevCourses as $courseData) {
            $course = Course::create([
                'user_id' => $mariaSantos->id,
                'slug' => Str::slug($courseData['title']) . '-' . Str::random(6),
                ...$courseData,
            ]);

            $this->command->info("✓ Course created: {$course->title}");
            $this->createLessonsForCourse($course);
        }

        // Courses for Juan dela Cruz (Data Science)
        $dataScienceCourses = [
            [
                'title' => 'Python for Data Science',
                'short_description' => 'Master Python programming for data analysis and visualization',
                'description' => "Learn Python programming specifically for data science applications. This course covers NumPy, Pandas, Matplotlib, and more.\n\nWhat you'll master:\n- Python fundamentals for data science\n- NumPy for numerical computing\n- Pandas for data manipulation\n- Data visualization with Matplotlib and Seaborn\n- Real-world data analysis projects",
                'is_free' => false,
                'price' => 59.99,
                'category' => 'Data Science',
                'level' => 'beginner',
                'language' => 'English',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Machine Learning Fundamentals',
                'short_description' => 'Introduction to machine learning algorithms and applications',
                'description' => "Understand the core concepts of machine learning and implement popular algorithms. Learn supervised and unsupervised learning techniques.\n\nCourse content:\n- Introduction to Machine Learning\n- Supervised Learning (Classification & Regression)\n- Unsupervised Learning (Clustering)\n- Model evaluation and validation\n- Scikit-learn library",
                'is_free' => false,
                'price' => 89.99,
                'category' => 'Data Science',
                'level' => 'intermediate',
                'language' => 'English',
                'is_published' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($dataScienceCourses as $courseData) {
            $course = Course::create([
                'user_id' => $juanDelaCruz->id,
                'slug' => Str::slug($courseData['title']) . '-' . Str::random(6),
                ...$courseData,
            ]);

            $this->command->info("✓ Course created: {$course->title}");
            $this->createLessonsForCourse($course);
        }

        // Courses for Ana Reyes (Mobile Development)
        $mobileCourses = [
            [
                'title' => 'React Native: Build Mobile Apps',
                'short_description' => 'Create cross-platform mobile applications with React Native',
                'description' => "Learn to build native mobile applications for iOS and Android using React Native. This course takes you from basics to deployment.\n\nYou'll learn:\n- React Native fundamentals\n- Navigation and routing\n- State management in mobile apps\n- Native device features\n- Publishing to App Store and Play Store",
                'is_free' => false,
                'price' => 69.99,
                'category' => 'Mobile Development',
                'level' => 'intermediate',
                'language' => 'English',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Flutter & Dart Complete Course',
                'short_description' => 'Build beautiful native apps with Flutter',
                'description' => "Master Flutter and Dart to create stunning mobile applications. Learn widgets, state management, animations, and more.\n\nCourse highlights:\n- Dart programming language\n- Flutter widgets and layouts\n- State management solutions\n- Animations and gestures\n- Firebase integration\n- App deployment",
                'is_free' => true,
                'category' => 'Mobile Development',
                'level' => 'beginner',
                'language' => 'English',
                'is_published' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($mobileCourses as $courseData) {
            $course = Course::create([
                'user_id' => $anaReyes->id,
                'slug' => Str::slug($courseData['title']) . '-' . Str::random(6),
                ...$courseData,
            ]);

            $this->command->info("✓ Course created: {$course->title}");
            $this->createLessonsForCourse($course);
        }

        // Create one draft course
        $draftCourse = Course::create([
            'user_id' => $mariaSantos->id,
            'title' => 'TypeScript Mastery (Coming Soon)',
            'slug' => Str::slug('TypeScript Mastery') . '-' . Str::random(6),
            'short_description' => 'Master TypeScript for building scalable applications',
            'description' => 'This course is currently under development. Stay tuned!',
            'is_free' => false,
            'price' => 59.99,
            'category' => 'Web Development',
            'level' => 'advanced',
            'language' => 'English',
            'is_published' => false,
        ]);

        $this->command->info("✓ Draft course created: {$draftCourse->title}");

        $this->command->info('');
        $this->command->info('Courses seeded successfully!');
        $this->command->info('Total courses created: ' . Course::count());
    }

    private function createLessonsForCourse(Course $course): void
    {
        $lessonTemplates = [
            // Beginner lessons
            'beginner' => [
                ['title' => 'Introduction and Course Overview', 'type' => 'video', 'duration' => 600, 'is_preview' => true],
                ['title' => 'Setting Up Your Environment', 'type' => 'video', 'duration' => 900, 'is_preview' => true],
                ['title' => 'Understanding the Basics', 'type' => 'video', 'duration' => 1200, 'is_preview' => false],
                ['title' => 'Your First Project', 'type' => 'video', 'duration' => 1500, 'is_preview' => false],
                ['title' => 'Common Pitfalls and Best Practices', 'type' => 'document', 'is_preview' => false],
                ['title' => 'Practice Exercises', 'type' => 'document', 'is_preview' => false],
                ['title' => 'Quiz: Test Your Knowledge', 'type' => 'document', 'is_preview' => false],
                ['title' => 'Final Project', 'type' => 'video', 'duration' => 1800, 'is_preview' => false],
            ],
            // Intermediate lessons
            'intermediate' => [
                ['title' => 'Welcome to the Course', 'type' => 'video', 'duration' => 480, 'is_preview' => true],
                ['title' => 'Prerequisites Review', 'type' => 'video', 'duration' => 720, 'is_preview' => true],
                ['title' => 'Advanced Concepts Part 1', 'type' => 'video', 'duration' => 1800, 'is_preview' => false],
                ['title' => 'Advanced Concepts Part 2', 'type' => 'video', 'duration' => 1800, 'is_preview' => false],
                ['title' => 'Practical Application', 'type' => 'video', 'duration' => 2100, 'is_preview' => false],
                ['title' => 'Code Examples and Resources', 'type' => 'document', 'is_preview' => false],
                ['title' => 'Building a Real Project', 'type' => 'video', 'duration' => 2400, 'is_preview' => false],
                ['title' => 'Testing and Debugging', 'type' => 'video', 'duration' => 1500, 'is_preview' => false],
                ['title' => 'Deployment Guide', 'type' => 'document', 'is_preview' => false],
                ['title' => 'Course Wrap-up and Next Steps', 'type' => 'video', 'duration' => 600, 'is_preview' => false],
            ],
            // Advanced lessons
            'advanced' => [
                ['title' => 'Course Introduction', 'type' => 'video', 'duration' => 420, 'is_preview' => true],
                ['title' => 'Expert Level Techniques', 'type' => 'video', 'duration' => 2400, 'is_preview' => false],
                ['title' => 'Architecture and Design Patterns', 'type' => 'video', 'duration' => 2700, 'is_preview' => false],
                ['title' => 'Performance Optimization', 'type' => 'video', 'duration' => 2100, 'is_preview' => false],
                ['title' => 'Security Best Practices', 'type' => 'document', 'is_preview' => false],
                ['title' => 'Scalability Strategies', 'type' => 'video', 'duration' => 1800, 'is_preview' => false],
                ['title' => 'Case Study: Real-world Application', 'type' => 'video', 'duration' => 3000, 'is_preview' => false],
                ['title' => 'Final Capstone Project', 'type' => 'document', 'is_preview' => false],
            ],
        ];

        $lessons = $lessonTemplates[$course->level] ?? $lessonTemplates['beginner'];

        foreach ($lessons as $index => $lessonData) {
            Lesson::create([
                'course_id' => $course->id,
                'title' => $lessonData['title'],
                'slug' => Str::slug($lessonData['title']) . '-' . Str::random(6),
                'position' => $index + 1,
                'type' => $lessonData['type'],
                'video_url' => $lessonData['type'] === 'video' ? 'https://youtube.com/watch?v=example' : null,
                'duration_seconds' => $lessonData['duration'] ?? null,
                'is_preview' => $lessonData['is_preview'],
            ]);
        }

        $this->command->info("  → Added " . count($lessons) . " lessons");
    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // Course Management
            [
                "name" => "Create Course",
                "slug" => "course.create",
                "description" => "Create new courses",
            ],
            [
                "name" => "Edit Course",
                "slug" => "course.edit",
                "description" => "Edit own courses",
            ],
            [
                "name" => "Delete Course",
                "slug" => "course.delete",
                "description" => "Delete own courses",
            ],
            [
                "name" => "Publish Course",
                "slug" => "course.publish",
                "description" => "Publish/unpublish courses",
            ],
            [
                "name" => "View All Courses",
                "slug" => "course.view-all",
                "description" => "View all courses in system",
            ],

            // Lesson Management
            [
                "name" => "Create Lesson",
                "slug" => "lesson.create",
                "description" => "Create lessons in own courses",
            ],
            [
                "name" => "Edit Lesson",
                "slug" => "lesson.edit",
                "description" => "Edit lessons in own courses",
            ],
            [
                "name" => "Delete Lesson",
                "slug" => "lesson.delete",
                "description" => "Delete lessons in own courses",
            ],

            // Enrollment Management
            [
                "name" => "Enroll Course",
                "slug" => "enrollment.enroll",
                "description" => "Enroll in courses",
            ],
            [
                "name" => "View Enrollments",
                "slug" => "enrollment.view",
                "description" => "View own enrollments",
            ],
            [
                "name" => "View Course Students",
                "slug" => "enrollment.view-students",
                "description" => "View students enrolled in own courses",
            ],

            // Rating and Comments
            [
                "name" => "Rate Course",
                "slug" => "course.rate",
                "description" => "Rate courses",
            ],
            [
                "name" => "Comment Course",
                "slug" => "course.comment",
                "description" => "Comment on courses",
            ],
            [
                "name" => "Like Comment",
                "slug" => "comment.like",
                "description" => "Like course comments",
            ],

            // Favorites
            [
                "name" => "Favorite Course",
                "slug" => "course.favorite",
                "description" => "Add courses to favorites",
            ],

            // Blog Management
            [
                "name" => "Create Blog",
                "slug" => "blog.create",
                "description" => "Create blog posts",
            ],
            [
                "name" => "Edit Blog",
                "slug" => "blog.edit",
                "description" => "Edit own blog posts",
            ],
            [
                "name" => "Delete Blog",
                "slug" => "blog.delete",
                "description" => "Delete own blog posts",
            ],
            [
                "name" => "Publish Blog",
                "slug" => "blog.publish",
                "description" => "Publish blog posts",
            ],

            // Admin Permissions
            [
                "name" => "Manage Users",
                "slug" => "user.manage",
                "description" => "Manage all users",
            ],
            [
                "name" => "Manage All Courses",
                "slug" => "course.manage-all",
                "description" => "Manage any course",
            ],
            [
                "name" => "Manage Roles",
                "slug" => "role.manage",
                "description" => "Manage roles and permissions",
            ],
            [
                "name" => "View Analytics",
                "slug" => "analytics.view",
                "description" => "View system analytics",
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ["slug" => $permission["slug"]],
                $permission,
            );
        }

        // Create Roles
        $studentRole = Role::firstOrCreate(
            ["slug" => "student"],
            [
                "name" => "Student",
                "description" => "Regular student who can enroll in courses",
            ],
        );

        $educatorRole = Role::firstOrCreate(
            ["slug" => "educator"],
            [
                "name" => "Educator",
                "description" => "Educator who can create and manage courses",
            ],
        );

        $adminRole = Role::firstOrCreate(
            ["slug" => "admin"],
            [
                "name" => "Administrator",
                "description" => "System administrator with full access",
            ],
        );

        // Assign Permissions to Student Role
        $studentPermissions = [
            "enrollment.enroll",
            "enrollment.view",
            "course.rate",
            "course.comment",
            "comment.like",
            "course.favorite",
        ];

        $studentRole->syncPermissions(
            Permission::whereIn("slug", $studentPermissions)
                ->pluck("id")
                ->toArray(),
        );

        // Assign Permissions to Educator Role (includes student permissions)
        $educatorPermissions = [
            // Student permissions
            "enrollment.enroll",
            "enrollment.view",
            "course.rate",
            "course.comment",
            "comment.like",
            "course.favorite",
            // Educator-specific permissions
            "course.create",
            "course.edit",
            "course.delete",
            "course.publish",
            "lesson.create",
            "lesson.edit",
            "lesson.delete",
            "enrollment.view-students",
            "blog.create",
            "blog.edit",
            "blog.delete",
            "blog.publish",
        ];

        $educatorRole->syncPermissions(
            Permission::whereIn("slug", $educatorPermissions)
                ->pluck("id")
                ->toArray(),
        );

        // Assign All Permissions to Admin Role
        $adminRole->syncPermissions(Permission::pluck("id")->toArray());

        $this->command->info("Roles and permissions seeded successfully!");
        $this->command->info("Created roles: Student, Educator, Administrator");
        $this->command->info("Total permissions: " . Permission::count());
    }
}

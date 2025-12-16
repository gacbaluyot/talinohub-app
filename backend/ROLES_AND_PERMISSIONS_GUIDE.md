# Scalable Roles and Permissions System

## Overview

This system implements a fully dynamic, database-driven role and permissions architecture. Roles and permissions are stored in the database and can be managed without code changes.

## Database Structure

```
users
  └── role_user (pivot) ──> roles
                              └── permission_role (pivot) ──> permissions
```

### Tables

1. **`roles`** - Dynamic roles (student, educator, admin, etc.)
   - `id`, `name`, `slug`, `description`

2. **`permissions`** - Dynamic permissions
   - `id`, `name`, `slug`, `description`

3. **`role_user`** - User-Role relationship (Many-to-Many)
   - `user_id`, `role_id`

4. **`permission_role`** - Role-Permission relationship (Many-to-Many)
   - `role_id`, `permission_id`

## Default Roles

### 1. Student (slug: `student`)
Permissions:
- `enrollment.enroll` - Enroll in courses
- `enrollment.view` - View own enrollments
- `course.rate` - Rate courses
- `course.comment` - Comment on courses
- `comment.like` - Like comments
- `course.favorite` - Add to favorites

### 2. Educator (slug: `educator`)
All student permissions plus:
- `course.create` - Create courses
- `course.edit` - Edit own courses
- `course.delete` - Delete own courses
- `course.publish` - Publish/unpublish courses
- `lesson.create` - Create lessons
- `lesson.edit` - Edit lessons
- `lesson.delete` - Delete lessons
- `enrollment.view-students` - View enrolled students
- `blog.create`, `blog.edit`, `blog.delete`, `blog.publish` - Blog management

### 3. Administrator (slug: `admin`)
All permissions including:
- `user.manage` - Manage all users
- `course.manage-all` - Manage any course
- `role.manage` - Manage roles and permissions
- `analytics.view` - View system analytics

## User Model Methods

### Assign Roles

```php
// Assign a role by slug
$user->assignRole('educator');

// Assign a role by ID
$user->assignRole(2);

// Assign a role by Role model
$role = Role::find(1);
$user->assignRole($role);

// Sync multiple roles (replaces all existing)
$user->syncRoles(['student', 'educator']);
```

### Check Roles

```php
// Check if user has a specific role
if ($user->hasRole('admin')) {
    // User is an admin
}

// Check if user has any of the given roles
if ($user->hasAnyRole(['educator', 'admin'])) {
    // User is either educator or admin
}

// Check if user has all the given roles
if ($user->hasAllRoles(['student', 'educator'])) {
    // User has both roles
}

// Convenience methods
if ($user->isStudent()) { }
if ($user->isEducator()) { }
if ($user->isAdmin()) { }
```

### Check Permissions

```php
// Check if user has a specific permission
if ($user->hasPermission('course.create')) {
    // User can create courses
}

// Check if user has any of the given permissions
if ($user->hasAnyPermission(['course.edit', 'course.delete'])) {
    // User can edit or delete
}

// Get all permissions for the user
$permissions = $user->getAllPermissions();
```

### Remove Roles

```php
// Remove a role
$user->removeRole('educator');

// Remove by ID or model
$user->removeRole(2);
```

## Role Model Methods

```php
$role = Role::where('slug', 'educator')->first();

// Give permission to role
$role->givePermissionTo('new.permission');
$role->givePermissionTo($permission); // Permission model

// Revoke permission
$role->revokePermissionTo('permission.slug');

// Check if role has permission
if ($role->hasPermission('course.create')) {
    // Role has this permission
}

// Sync permissions (replaces all)
$role->syncPermissions([1, 2, 3]); // Permission IDs
```

## Using Middleware in Routes

### Check Role

```php
// Single role
Route::post('/courses', [CourseController::class, 'store'])
    ->middleware(['auth:sanctum', 'role:educator']);

// Multiple roles (user needs ANY of these)
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth:sanctum', 'role:admin,educator']);
```

### Check Permission

```php
// Single permission
Route::post('/courses', [CourseController::class, 'store'])
    ->middleware(['auth:sanctum', 'permission:course.create']);

// Multiple permissions (user needs ANY of these)
Route::delete('/courses/{id}', [CourseController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'permission:course.delete,course.manage-all']);
```

## Using in Controllers

```php
class CourseController extends Controller
{
    public function store(Request $request)
    {
        // Check permission
        if (!$request->user()->hasPermission('course.create')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        // Create course
    }
    
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        
        // Check if user is the owner OR has admin permission
        if ($course->user_id !== $request->user()->id && 
            !$request->user()->hasPermission('course.manage-all')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        // Update course
    }
}
```

## Using Blade Directives (if needed for web routes)

```php
@role('admin')
    <a href="/admin">Admin Panel</a>
@endrole

@hasrole('educator')
    <a href="/courses/create">Create Course</a>
@endhasrole

@haspermission('course.create')
    <button>New Course</button>
@endhaspermission
```

## Managing Roles Dynamically

### Create New Role

```php
$role = Role::create([
    'name' => 'Content Moderator',
    'slug' => 'moderator',
    'description' => 'Can moderate content'
]);

// Assign permissions
$permissions = Permission::whereIn('slug', [
    'course.view-all',
    'comment.delete',
    'blog.edit'
])->pluck('id');

$role->syncPermissions($permissions);
```

### Create New Permission

```php
$permission = Permission::create([
    'name' => 'Export Data',
    'slug' => 'data.export',
    'description' => 'Export system data'
]);

// Assign to roles
$adminRole = Role::where('slug', 'admin')->first();
$adminRole->givePermissionTo($permission);
```

### Modify Role Permissions

```php
$educatorRole = Role::where('slug', 'educator')->first();

// Add a new permission
$educatorRole->givePermissionTo('analytics.view');

// Remove a permission
$educatorRole->revokePermissionTo('blog.delete');

// Replace all permissions
$newPermissions = Permission::whereIn('slug', [
    'course.create',
    'course.edit',
    'lesson.create'
])->pluck('id');

$educatorRole->syncPermissions($newPermissions);
```

## Registration Flow Example

```php
// In your registration controller
public function register(Request $request)
{
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
    
    // Assign default student role
    $user->assignRole('student');
    
    // If they selected educator during registration
    if ($request->account_type === 'educator') {
        $user->assignRole('educator');
    }
    
    return response()->json([
        'user' => $user,
        'roles' => $user->roles,
        'permissions' => $user->getAllPermissions()
    ]);
}
```

## Login Response with Roles

```php
// In your login controller
public function login(Request $request)
{
    // ... authentication logic ...
    
    $token = $user->createToken('auth_token')->plainTextToken;
    
    return response()->json([
        'success' => true,
        'user' => $user,
        'roles' => $user->roles->pluck('slug'), // ['student', 'educator']
        'permissions' => $user->getAllPermissions()->pluck('slug'),
        'token' => $token
    ]);
}
```

## Frontend Integration

When a user logs in, store their roles and permissions:

```javascript
// Login response
{
    "user": { ... },
    "roles": ["educator"],
    "permissions": [
        "course.create",
        "course.edit",
        "lesson.create",
        ...
    ],
    "token": "..."
}

// In your frontend
localStorage.setItem('userRoles', JSON.stringify(response.roles));
localStorage.setItem('userPermissions', JSON.stringify(response.permissions));

// Check permissions in frontend
function hasPermission(permission) {
    const permissions = JSON.parse(localStorage.getItem('userPermissions'));
    return permissions.includes(permission);
}

// Show/hide UI elements
if (hasPermission('course.create')) {
    // Show create course button
}
```

## Best Practices

1. **Use Permissions for Authorization** - Not roles. Check permissions in controllers.
2. **Roles Group Permissions** - A role is just a collection of permissions.
3. **Dynamic Management** - Add new roles/permissions without code changes.
4. **Principle of Least Privilege** - Give users only the permissions they need.
5. **Cache Permissions** - Consider caching user permissions for performance.
6. **Audit Changes** - Log when roles/permissions are modified.

## Scalability Features

✅ **Database-driven** - All roles and permissions in database
✅ **Many-to-Many** - Users can have multiple roles
✅ **Flexible** - Add new roles/permissions without code changes
✅ **Hierarchical** - Roles inherit and combine permissions
✅ **Queryable** - Can check permissions at database level
✅ **API-ready** - Easy to build admin UI for role management

## Migration and Seeding

```bash
# Run migrations
php artisan migrate

# Seed default roles and permissions
php artisan db:seed --class=RolesAndPermissionsSeeder

# Reseed if you add new permissions
php artisan db:seed --class=RolesAndPermissionsSeeder
```

## Example: Building Admin Panel for Role Management

```php
// API Routes
Route::middleware(['auth:sanctum', 'permission:role.manage'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
    
    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::post('/roles/{roleId}/permissions', [RoleController::class, 'syncPermissions']);
});

// Role Controller
public function index()
{
    return Role::with('permissions')->get();
}

public function syncPermissions(Request $request, $roleId)
{
    $role = Role::findOrFail($roleId);
    $role->syncPermissions($request->permission_ids);
    
    return response()->json([
        'success' => true,
        'role' => $role->load('permissions')
    ]);
}
```

This system is fully scalable and can grow with your application needs!

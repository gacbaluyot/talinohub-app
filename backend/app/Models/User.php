<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ["name", "email", "password", "address", "role"];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ["password", "remember_token"];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function ownedCourses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        // courses this user created as an educator
        return $this->hasMany(Course::class);
    }

    public function enrolledCourses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        // courses this user is enrolled in as a student
        return $this->belongsToMany(Course::class, "course_user")
            ->withPivot(["enrolled_at", "completed_at", "progress"])
            ->withTimestamps();
    }

    public function favoriteCourses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        // courses this user has favorited/bookmarked
        return $this->belongsToMany(
            Course::class,
            "course_favorites",
        )->withTimestamps();
    }

    public function courseRatings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseRating::class);
    }

    public function courseComments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseComment::class);
    }

    public function blogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Blog::class);
    }

    /**
     * Get the roles that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Role>
     */
    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, "role_user")->withTimestamps();
    }

    /**
     * Assign a role to the user.
     *
     * @param Role|int|string $role
     * @return void
     */
    public function assignRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where("slug", $role)->firstOrFail();
        }

        $roleId = $role instanceof Role ? $role->id : $role;

        if (!$this->roles()->where("role_id", $roleId)->exists()) {
            $this->roles()->attach($roleId);
        }
    }

    /**
     * Remove a role from the user.
     *
     * @param Role|int|string $role
     * @return void
     */
    public function removeRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where("slug", $role)->first();
            if (!$role) {
                return;
            }
        }

        $roleId = $role instanceof Role ? $role->id : $role;
        $this->roles()->detach($roleId);
    }

    /**
     * Sync roles to the user.
     *
     * @param array<int|string> $roles
     * @return void
     */
    public function syncRoles(array $roles): void
    {
        $roleIds = collect($roles)
            ->map(function ($role) {
                if (is_string($role)) {
                    return Role::where("slug", $role)->firstOrFail()->id;
                }
                return $role instanceof Role ? $role->id : $role;
            })
            ->toArray();

        $this->roles()->sync($roleIds);
    }

    /**
     * Check if user has a specific role.
     *
     * @param string $roleSlug
     * @return bool
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->roles()->where("slug", $roleSlug)->exists();
    }

    /**
     * Check if user has any of the given roles.
     *
     * @param array<string> $roles
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn("slug", $roles)->exists();
    }

    /**
     * Check if user has all of the given roles.
     *
     * @param array<string> $roles
     * @return bool
     */
    public function hasAllRoles(array $roles): bool
    {
        return $this->roles()->whereIn("slug", $roles)->count() ===
            count($roles);
    }

    /**
     * Check if user is a student.
     *
     * @return bool
     */
    public function isStudent(): bool
    {
        return $this->hasRole("student");
    }

    /**
     * Check if user is an educator.
     *
     * @return bool
     */
    public function isEducator(): bool
    {
        return $this->hasRole("educator");
    }

    /**
     * Check if user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole("admin");
    }

    /**
     * Check if user has a specific permission (through roles).
     *
     * @param string $permissionSlug
     * @return bool
     */
    public function hasPermission(string $permissionSlug): bool
    {
        return $this->roles()
            ->whereHas("permissions", function ($query) use ($permissionSlug) {
                $query->where("slug", $permissionSlug);
            })
            ->exists();
    }

    /**
     * Check if user has any of the given permissions.
     *
     * @param array<string> $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->roles()
            ->whereHas("permissions", function ($query) use ($permissions) {
                $query->whereIn("slug", $permissions);
            })
            ->exists();
    }

    /**
     * Get all permissions for the user (through all their roles).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPermissions()
    {
        return Permission::whereHas("roles", function ($query) {
            $query->whereIn("role_id", $this->roles()->pluck("id"));
        })->get();
    }
}

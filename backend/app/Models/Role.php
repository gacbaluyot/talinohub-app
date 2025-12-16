<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ["name", "slug", "description"];

    /**
     * Get the users that have this role.
     *
     * @return BelongsToMany<User>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, "role_user")->withTimestamps();
    }

    /**
     * Get the permissions that belong to this role.
     *
     * @return BelongsToMany<Permission>
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            "permission_role",
        )->withTimestamps();
    }

    /**
     * Give a permission to this role.
     *
     * @param Permission|int $permission
     * @return void
     */
    public function givePermissionTo($permission): void
    {
        $permissionId =
            $permission instanceof Permission ? $permission->id : $permission;

        if (
            !$this->permissions()
                ->where("permission_id", $permissionId)
                ->exists()
        ) {
            $this->permissions()->attach($permissionId);
        }
    }

    /**
     * Revoke a permission from this role.
     *
     * @param Permission|int $permission
     * @return void
     */
    public function revokePermissionTo($permission): void
    {
        $permissionId =
            $permission instanceof Permission ? $permission->id : $permission;
        $this->permissions()->detach($permissionId);
    }

    /**
     * Check if role has a specific permission.
     *
     * @param string $permissionSlug
     * @return bool
     */
    public function hasPermission(string $permissionSlug): bool
    {
        return $this->permissions()->where("slug", $permissionSlug)->exists();
    }

    /**
     * Sync permissions to this role.
     *
     * @param array<int> $permissionIds
     * @return void
     */
    public function syncPermissions(array $permissionIds): void
    {
        $this->permissions()->sync($permissionIds);
    }
}

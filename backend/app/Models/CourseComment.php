<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "user_id",
        "course_id",
        "parent_id",
        "comment",
        "likes_count",
    ];

    protected $with = ["user:id,name"];

    protected $casts = [
        "likes_count" => "integer",
    ];

    /*
     * Relationships
     * @return BelongsTo<User,CourseComment>
     */

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * @return BelongsTo<Course,CourseComment>
     */
    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    /**
     * @return BelongsTo<CourseComment,CourseComment>
     */
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CourseComment::class, "parent_id");
    }

    public function replies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseComment::class, "parent_id")
            ->with("replies")
            ->latest();
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            "course_comment_likes",
            "course_comment_id",
        )->withTimestamps();
    }

    /*
     * Scopes
     * @param mixed $query
     */

    public function scopeTopLevel($query)
    {
        return $query->whereNull("parent_id");
    }
}

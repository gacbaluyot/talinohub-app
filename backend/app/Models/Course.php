<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'short_description',
        'description',
        'is_free',
        'price',
        'thumbnail_path',
        'category',
        'level',
        'language',
        'average_rating',
        'ratings_count',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_free'      => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /*
     * Relationships
     */

    public function educator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        // owner of the course
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lessons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Lesson::class)
            ->orderBy('position');
    }

    public function students(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        // users enrolled in this course
        return $this->belongsToMany(User::class, 'course_user')
            ->withPivot(['enrolled_at', 'completed_at', 'progress'])
            ->withTimestamps();
    }

    public function favoritedBy(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        // users who favorited this course
        return $this->belongsToMany(User::class, 'course_favorites')
            ->withTimestamps();
    }

    public function ratings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseRating::class);
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseComment::class);
    }

    /*
     * Model events
     */

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title) . '-' . Str::random(6);
            }
        });

        static::updating(function (Course $course) {
            // Optional: update slug if title changed and slug not manually set
            if ($course->isDirty('title') && $course->isDirty('slug') === false) {
                $course->slug = Str::slug($course->title) . '-' . Str::random(6);
            }
        });
    }
}

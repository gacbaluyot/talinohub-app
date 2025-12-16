<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property \Illuminate\Support\Carbon $enrolled_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property int $progress
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class CourseUser extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "course_user";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "user_id",
        "course_id",
        "enrolled_at",
        "completed_at",
        "progress",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "enrolled_at" => "datetime",
        "completed_at" => "datetime",
        "progress" => "integer",
    ];

    /**
     * Get the user associated with this enrollment.
     *
     * @return BelongsTo<User, CourseUser>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course associated with this enrollment.
     *
     * @return BelongsTo<Course, CourseUser>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Check if the enrollment is completed.
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed_at !== null || $this->progress === 100;
    }

    /**
     * Mark the enrollment as completed.
     *
     * @return bool
     */
    public function markAsCompleted(): bool
    {
        $this->completed_at = now();
        $this->progress = 100;
        return $this->save();
    }

    /**
     * Update the progress of the enrollment.
     *
     * @param int $progress
     * @return bool
     */
    public function updateProgress(int $progress): bool
    {
        $this->progress = min(100, max(0, $progress));

        // Auto-complete if progress reaches 100%
        if ($this->progress === 100 && $this->completed_at === null) {
            $this->completed_at = now();
        }

        return $this->save();
    }

    /**
     * Get the enrollment duration in days.
     *
     * @return int
     */
    public function enrollmentDurationInDays(): int
    {
        return $this->enrolled_at->diffInDays($this->completed_at ?? now());
    }

    /**
     * Scope a query to only include completed enrollments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull("completed_at");
    }

    /**
     * Scope a query to only include active (in-progress) enrollments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNull("completed_at");
    }

    /**
     * Scope a query to filter by minimum progress.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $minProgress
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithMinProgress($query, int $minProgress)
    {
        return $query->where("progress", ">=", $minProgress);
    }
}

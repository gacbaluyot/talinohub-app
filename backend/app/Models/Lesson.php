<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'position',
        'type',
        'video_url',
        'document_path',
        'duration_seconds',
        'is_preview',
    ];

    protected $casts = [
        'position'         => 'integer',
        'duration_seconds' => 'integer',
        'is_preview'       => 'boolean',
    ];

    /*
     * Relationships
     */

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /*
     * Model events
     */

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Lesson $lesson) {
            if (empty($lesson->slug)) {
                $lesson->slug = Str::slug($lesson->title) . '-' . Str::random(6);
            }

            // If no position given, put it at the end
            if (empty($lesson->position)) {
                $maxPosition = static::where('course_id', $lesson->course_id)->max('position');
                $lesson->position = $maxPosition ? $maxPosition + 1 : 1;
            }
        });
    }
}

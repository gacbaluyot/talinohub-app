<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseVideo extends Model
{
    protected $fillable = [
        'course_id',
        'filename',
        'path',
        'is_free',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseFile extends Model
{
    protected $fillable = [
        'course_id',
        'filename',
        'path',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

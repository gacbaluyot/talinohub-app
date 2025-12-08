<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'instructor_id',
        'title',
        'description',
        'price',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function files() : HasMany
    {
        return $this->hasMany(CourseFile::class);
    }

    public function videos() : HasMany
    {
        return $this->hasMany(CourseVideo::class);
    }
}

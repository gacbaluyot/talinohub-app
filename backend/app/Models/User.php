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
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
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
        return $this->belongsToMany(Course::class, 'course_user')
            ->withPivot(['enrolled_at', 'completed_at', 'progress'])
            ->withTimestamps();
    }

    public function favoriteCourses(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        // courses this user has favorited/bookmarked
        return $this->belongsToMany(Course::class, 'course_favorites')
            ->withTimestamps();
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
}

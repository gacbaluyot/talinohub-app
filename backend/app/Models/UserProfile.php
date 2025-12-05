<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'bio',
        'filename',
        'profile_picture',
        'credentials',
    ];

    protected $casts = [
        'credentials' => 'array',
    ]; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

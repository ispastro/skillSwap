<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'bio', 'city', 'country',
        'availability', 'skills_offered', 'skills_needed', 'profile_picture',
        'token_balance', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'availability' => 'array',
        'skills_offered' => 'array',
        'skills_needed' => 'array',
    ];

    public function hasCompletedProfile():boolval{
        return $this->bio
            && $this->city
            && $this->country
            && is_array($this->skills_offered) && count($this->skills_offered) > 0
            && is_array($this->skills_needed) && count($this->skills_needed) > 0
            && $this->profile_picture
            && $this->profile_completed;
    }
}
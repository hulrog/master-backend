<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public function country() {
        return $this->belongsTo(Country::class);
    }
    public function expertiseRatings() {
        return $this->hasMany(ExpertiseRating::class);
    }
    public function expertise() {
        return $this->hasMany(Expertise::class);
    }
    public function facts() {
        return $this->hasMany(Fact::class);
    }

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'password',
        'date_of_birth',
        'date_joined',
        'name',
        'email',
        'gender',
        'country_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = false; 

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}

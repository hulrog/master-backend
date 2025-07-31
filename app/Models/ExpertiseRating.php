<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertiseRating extends Model
{
    /** @use HasFactory<\Database\Factories\ExpertiseRatingFactory> */
    use HasFactory;

    public function expertise() {
        return $this->belongsTo(Expertise::class, 'expertise_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');  
    }

    protected $primaryKey = 'expertise_rating_id';

    protected $fillable = [
        'expertise_id',
        'user_id',
        'rating',
        'date_rated'
    ];

    public $timestamps = false; 
}

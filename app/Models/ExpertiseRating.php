<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertiseRating extends Model
{
    /** @use HasFactory<\Database\Factories\ExpertiseRatingFactory> */
    use HasFactory;

    public function expertise() {
        return $this->belongsTo(Expertise::class);
    }
    public function user() {
        return $this->belongsTo(User::class);  
    }

    protected $primaryKey = 'expertise_rating_id';

    protected $fillable = [
        'expertise_id',
        'user_id',
        'rating',
        'date_rated'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expertise extends Model
{
    /** @use HasFactory<\Database\Factories\ExpertiseFactory> */
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function area() {
        return $this->belongsTo(Area::class, 'area_id');
    }
    public function ratings()
    {
        return $this->hasMany(ExpertiseRating::class, 'expertise_id');
    }
    
    protected $primaryKey = 'expertise_id'; 

    protected $fillable = [
        'user_id',
        'area_id',
    ];

    public $timestamps = false; 
}

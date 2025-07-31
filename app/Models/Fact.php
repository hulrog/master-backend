<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fact extends Model
{
    /** @use HasFactory<\Database\Factories\FactFactory> */
    use HasFactory;


    public function topic() {
        return $this->belongsTo(Topic::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function factVotes() {
        return $this->hasMany(FactVote::class);
    }

    protected $primaryKey = 'fact_id';

    protected $fillable = [
        'topic_id',
        'user_id',
        'text',
        'date_entered',
        'source',
    ];

    public $timestamps = false; 
}

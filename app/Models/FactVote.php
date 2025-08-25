<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactVote extends Model
{
    /** @use HasFactory<\Database\Factories\FactVoteFactory> */
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function fact() {
        return $this->belongsTo(Fact::class, 'fact_id');
    }

    protected $primaryKey = 'fact_vote_id';

    protected $fillable = [
        'fact_id',
        'user_id',
        'rating',
        'weight',
    ];

    public $timestamps = false; 
}


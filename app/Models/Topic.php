<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    /** @use HasFactory<\Database\Factories\TopicFactory> */
    use HasFactory;

    public function facts() {return $this->hasMany(Fact::class);}  
    public function area() {return $this->belongsTo(Area::class);} 

    protected $primaryKey = 'topic_id';

    protected $fillable = [
        'name',
        'area_id',
    ];
}

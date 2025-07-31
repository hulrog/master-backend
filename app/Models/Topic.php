<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    /** @use HasFactory<\Database\Factories\TopicFactory> */
    use HasFactory;

    public function facts() {
        return $this->hasMany(Fact::class, 'topic_id');
    }  
    public function area() {
        return $this->belongsTo(Area::class, 'area_id');
    } 

    protected $primaryKey = 'topic_id';

    protected $fillable = [
        'name',
        'area_id',
    ];

    public $timestamps = false; 
}

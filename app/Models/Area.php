<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    /** @use HasFactory<\Database\Factories\AreaFactory> */
    use HasFactory;

    public function topics() {return $this->hasMany(Topic::class);}

    protected $primaryKey = 'area_id';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false; 
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /** @use HasFactory<\Database\Factories\CountryFactory> */
    use HasFactory;

    public function users() {
        return $this->hasMany(User::class, 'country_id');
    }

    protected $primaryKey = 'country_id';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false; 
}

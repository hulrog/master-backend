<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /** @use HasFactory<\Database\Factories\CountryFactory> */
    use HasFactory;

    public function users() {return $this->hasMany(User::class);}

    protected $primaryKey = 'country_id';

    protected $fillable = [
        'name',
    ];
}

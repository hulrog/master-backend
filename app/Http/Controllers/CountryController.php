<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function getAllCountries()
    {
        $countries = Country::all();
        if ($countries->isEmpty()) {
            return response()->json(['message' => 'No countries found'], 404);
        }
        return response()->json(['message' => 'Countries retrieved successfully', 'countries' => $countries], 200);
    }
}

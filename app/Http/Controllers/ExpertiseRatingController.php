<?php

namespace App\Http\Controllers;

use App\Models\ExpertiseRating;
use Illuminate\Http\Request;

class ExpertiseRatingController extends Controller
{
    public function getAllExpertiseRatings()
    {
        $ratings = ExpertiseRating::all();
        if ($ratings->isEmpty()) {
            return response()->json(['message' => 'No expertise ratings found'], 404);
        }
        return response()->json(['message' => 'Expertise ratings retrieved successfully', 'expertise_ratings' => $ratings], 200);
    }

    public function createExpertiseRating(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'expertise_id' => 'required|integer',
            'rating' => 'required|integer',
        ]);
        $validatedData['date_rated'] = now();
        $rating = ExpertiseRating::create($validatedData);
        return response()->json(['message' => 'Expertise rating created successfully', 'expertise_rating' => $rating], 201);
    }

    public function getAllExpertiseRatingsOfExpertise($expertiseId)
    {
        $ratings = ExpertiseRating::where('expertise_id', $expertiseId)->get();
        if ($ratings->isEmpty()) {
            return response()->json(['message' => 'No expertise ratings found for this expertise'], 404);
        }
        return response()->json(['message' => 'Expertise ratings retrieved successfully', 'expertise_ratings' => $ratings], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Expertise;
use Illuminate\Http\Request;

class ExpertiseController extends Controller
{
    public function getAllExpertises()
    {
        $expertises = Expertise::all();
        if ($expertises->isEmpty()) {
            return response()->json(['message' => 'No expertises found'], 404);
        }
        return response()->json(['message' => 'Expertises retrieved successfully', 'expertises' => $expertises], 200);
    }

    public function createExpertise(Request $request)
    {
         $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'area_id' => 'required|integer',
        ]);
        $expertise = Expertise::create($validatedData);
        return response()->json(['message' => 'Expertise created successfully', 'expertise' => $expertise], 201);
    }

    public function deleteExpertise($id)
    {
        $expertise = Expertise::find($id);
        if (!$expertise) {
            return response()->json(['message' => 'Expertise not found'], 404);
        }
        $expertise->delete();
        return response()->json(['message' => 'Expertise deleted successfully'], 200);
    }

    public function getAllExpertisesOfUser($userId)
    {
        $expertises = Expertise::where('user_id', $userId)
            ->with('area')
            ->withSum('ratings as total_rating', 'rating')
            ->get()
            ->map(function ($expertise) {
                return [
                    'expertise_id' => $expertise->expertise_id,
                    'name' => $expertise->area->name ?? 'Unknown',
                    'total_rating' => $expertise->total_rating ?? 0,
                ];
        });

        if ($expertises->isEmpty()) {
            return response()->json(['message' => 'No expertises found for this user'], 404);
        }

        return response()->json(['expertises' => $expertises], 200);
    }
}

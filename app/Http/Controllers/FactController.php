<?php

namespace App\Http\Controllers;

use App\Models\Fact;
use Illuminate\Http\Request;

class FactController extends Controller
{
    public function getAllFacts()
    {
        $facts = Fact::all();
        if ($facts->isEmpty()) {
            return response()->json(['message' => 'No facts found'], 404);
        }
        return response()->json(['message' => 'Facts retrieved successfully', 'facts' => $facts], 200);
    }

    public function createFact(Request $request)
    {
        $validatedData = $request->validate([
            'text' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'user_id' => 'required|integer',
            'topic_id' => 'required|integer',
        ]);
        $validatedData['date_entered'] = now();
        $fact = Fact::create($validatedData);
        return response()->json(['message' => 'Fact created successfully', 'fact' => $fact], 201);
    }

    public function getAllFactsOfUser($userId)
    {
        $facts = Fact::where('user_id', $userId)->get();
        if ($facts->isEmpty()) {
            return response()->json(['message' => 'No facts found for this user'], 404);
        }
        return response()->json(['message' => 'Facts retrieved successfully', 'facts' => $facts], 200);
    }
}

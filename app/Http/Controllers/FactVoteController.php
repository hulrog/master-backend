<?php

namespace App\Http\Controllers;

use App\Models\FactVote;
use Illuminate\Http\Request;

class FactVoteController extends Controller
{
    public function getAllFactVotesOfFact($factId)
    {
        $votes = FactVote::where('fact_id', $factId)->get();
        if ($votes->isEmpty()) {
            return response()->json(['message' => 'No fact votes found for this fact'], 404);
        }
        return response()->json(['message' => 'Fact votes retrieved successfully', 'fact_votes' => $votes], 200);
    }

    public function createFactVote(Request $request)
    {
        $validatedData = $request->validate([
            'fact_id' => 'required|integer',
            'user_id' => 'required|integer',
            'vote' => 'required|integer',
        ]);
        $factVote = FactVote::create($validatedData);
        return response()->json(['message' => 'Fact vote created successfully', 'fact_vote' => $factVote], 201);
    }
}

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
            'rating' => 'required|boolean',
        ]);

         // Delete any previous vote for this fact by this user
        FactVote::where('fact_id', $validatedData['fact_id'])
            ->where('user_id', $validatedData['user_id'])
            ->delete();
            
        $factVote = FactVote::create($validatedData);
        return response()->json(['message' => 'Fact vote created successfully', 'fact_vote' => $factVote], 201);
    }
}

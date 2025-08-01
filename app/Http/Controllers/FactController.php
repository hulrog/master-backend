<?php

namespace App\Http\Controllers;

use App\Models\Fact;
use App\Models\User;
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
    public function getAllFactsOfTopic($topicId)
    {
        $facts = Fact::where('topic_id', $topicId)->get();
        if ($facts->isEmpty()) {
            return response()->json(['message' => 'No facts found for this topic'], 404);
        }
        return response()->json(['message' => 'Facts retrieved successfully', 'facts' => $facts], 200);
    }

    public function getAllFactsThatMeetRequirements(Request $request)
    {
        $topicId = $request->input('topicId');
        $countries = $request->input('countries', []);
        $genders = $request->input('gender', []);

        if (!$topicId) {
            return response()->json(['error' => 'topicId is required'], 400);
        }

        $facts = Fact::where('topic_id', $topicId)
            ->whereHas('factVotes', function ($voteQuery) use ($countries, $genders) {
                $voteQuery->where('rating', true)
                    ->whereHas('user', function ($userQuery) use ($countries, $genders) {
                        if (!empty($countries)) {
                            $userQuery->whereIn('country_id', $countries);
                        }

                        if (!empty($genders)) {
                            $userQuery->whereIn('gender', $genders);
                        }
                    });
                })
                // ->with('factVotes.user')
                // ->get();
                -> pluck('text');

        if ($facts->isEmpty()) {
            return response()->json(['message' => 'No facts found that meet the requirements'], 404);
        }

        return response()->json(['message' => 'Facts retrieved successfully', 'facts' => $facts], 200);
     }

    public function getAllFactsThatMeetRequirementsv2(Request $request)
    {
        $topicId = $request->input('topicId');
        $countries = $request->input('countries', []);
        $genders = $request->input('gender', []);

        if (!$topicId) {
            return response()->json(['error' => 'topicId is required'], 400);
        }

        // 1) Pokupi korisnike koji ispunjavaju kriterijume
        $userQuery = User::query();

        if (!empty($countries)) {
            $userQuery->whereIn('country_id', $countries);
        }

        if (!empty($genders)) {
            $userQuery->whereIn('gender', $genders);
        }

        $userIds = $userQuery->pluck('user_id');
        $eligibleUserCount = $userIds->count();

        if ($eligibleUserCount === 0) {
            return response()->json(['message' => 'No users match the given filters'], 404);
        }

        // 2) Pokupi cinjenice za koje su glasali da su istiniti
        $facts = Fact::where('topic_id', $topicId)
            ->with(['factVotes' => function ($query) use ($userIds) {
                $query->whereIn('user_id', $userIds)
                    ->where('rating', true);
            }])
            ->get();

        // 3) Filtriraj samo cinjenice koje vecina korisniak podrzava
        $filtered = $facts->filter(function ($fact) use ($eligibleUserCount) {
            return $fact->factVotes->count() > ($eligibleUserCount / 2);
        });

        if ($filtered->isEmpty()) {
            return response()->json(['message' => 'No facts met the voting threshold'], 404);
        }

        $texts = $filtered->pluck('text')->values();

        return response()->json(['facts' => $texts], 200);
    }


}

<?php

namespace App\Http\Controllers;

use App\Models\Fact;
use App\Models\User;
use App\Models\Topic;
use App\Models\Area;
use Illuminate\Http\Request;

class FactController extends Controller
{
    public function getAllFacts()
    {
        $facts = Fact::with(['user', 'topic'])->get();
        if ($facts->isEmpty()) {
            return response()->json(['message' => 'No facts found'], 404);
        }

        $formattedFacts = $facts->map(function ($fact) {
            return [
                'fact_id'    => $fact->fact_id,
                'text'       => $fact->text,
                'date_entered' => $fact->date_entered,
                'source'     => $fact->source,
                'user_id'    => $fact->user_id,
                'user_name'  => $fact->user->name ?? null,
                'topic_id'   => $fact->topic_id,
                'topic_name' => $fact->topic->name ?? null,
            ];
        });
        return response()->json(['message' => 'Facts retrieved successfully', 'facts' => $formattedFacts], 200);
    }

    public function getInterestingFacts(Request $request)
    {
        $user = $request->user(); 

        $facts = Fact::with(['user', 'topic', 'factVotes'])->get();
        
        if ($facts->isEmpty()) {
            return response()->json(['message' => 'No facts found'], 404);
        }

        $formattedFacts = $facts->map(function ($fact) use ($user) {
            $userVote = null;
            if ($user) {
                $vote = $fact->factVotes->firstWhere('user_id', $user->user_id);
                $userVote = $vote->rating ?? null;
            }

            // Broj true i false glasova
            $trueRatings = $fact->factVotes->where('rating', true)->sum('weight');
            $falseRatings= $fact->factVotes->where('rating', false)->sum('weight');

            // Top drzave za true i false
            $trueCountry = $fact->factVotes
            ->where('rating', true)
                ->groupBy('user.country_id')
                ->map(function ($votes) {
                    $country = optional($votes->first()->user->country);
                    return [
                        'code' => $country->code ?? null,
                        'weight' => $votes->sum('weight'),
                    ];
                })
                ->sortByDesc('weight')
                ->first();

             $falseCountry = $fact->factVotes
                ->where('rating', false)
                ->groupBy('user.country_id')
                ->map(function ($votes) {
                    $country = optional($votes->first()->user->country);
                    return [
                        'code' => $country->code ?? null,
                        'weight' => $votes->sum('weight'),
                    ];
                })
                ->sortByDesc('weight')
                ->first();

            return [
                'fact_id'    => $fact->fact_id,
                'text'       => $fact->text,
                'date_entered' => $fact->date_entered,
                'source'     => $fact->source,
                'user_id'    => $fact->user_id,
                'user_name'  => $fact->user->name ?? null,
                'topic_id'   => $fact->topic_id,
                'topic_name' => $fact->topic->name ?? null,
                'user_rating'  => $userVote,
                'true_ratings' => $trueRatings,
                'false_ratings' => $falseRatings,
                'true_country'  => $trueCountry['code'] ?? null,
                'false_country' => $falseCountry['code'] ?? null,
            ];
        });
        return response()->json(['message' => 'Facts retrieved successfully', 'facts' => $formattedFacts], 200);
    }

    public function createFact(Request $request)
    {
        $validatedData = $request->validate([
            'text'              => 'required|string|max:255',
            'source'            => 'required|string|max:255',
            'user_id'           => 'required|integer',
            'topic_id'          => 'nullable|integer', // nullable if new topic
            'area_id'           => 'nullable|integer', // nullable if new area
            'new_area_name'     => 'nullable|string|max:255', // only if creating new area
            'new_topic_name'    => 'nullable|string|max:255', // only if creating new topic
        ]);

        $validatedData['date_entered'] = now();

        // Create new area if user typed one
        $newAreaName = $validatedData['new_area_name'] ?? null;
        if ($newAreaName) {
            $area = Area::create(['name' => $newAreaName]);
            $validatedData['area_id'] = $area->area_id;
        }

        // Create new topic if user typed one
        $newTopicName = $validatedData['new_topic_name'] ?? null;
        if ($newTopicName) {
            $topic = Topic::create([
                'name' => $newTopicName,
                'area_id' => $validatedData['area_id'],
            ]);
            $validatedData['topic_id'] = $topic->topic_id;
        }

        $validatedData['area_id'] = null;
        $validatedData['new_area_name'] = null;
        $validatedData['new_topic_name'] = null;

        $fact = Fact::create($validatedData);

        return response()->json([
            'message' => 'Fact created successfully',
            'fact' => $fact
        ], 201);
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

        // 1) Pokupi korisnike koji ispunjavaju uslove
        $userQuery = User::query();
        if (!empty($countries)) {
            $userQuery->whereIn('country_id', $countries);
        }
        if (!empty($genders)) {
            $userQuery->whereIn('gender', $genders);
        }
        $userIds = $userQuery->pluck('user_id');
        if ($userIds->isEmpty()) {
            return response()->json(['message' => 'No users match the given filters'], 404);
        }

        // 2) Pokupi sve činjenice koje pripadaju temi i imaju glasove od tih korisnika
        $facts = Fact::where('topic_id', $topicId)
            ->with(['factVotes' => function ($query) use ($userIds) {
                $query->whereIn('user_id', $userIds);
            }])
            ->get();

        // 3) Filtriraj činjenice za koje su ovi korisnici glasali
        $filtered = $facts->filter(function ($fact) use ($userIds) {
            $votes = $fact->factVotes;
            $totalVotes = $votes->count();
            $trueVotes = $votes->where('rating', true)->count();

            info("Fact ID {$fact->fact_id} - Total Votes: $totalVotes, True Votes: $trueVotes");

            return $totalVotes > 0 && ($trueVotes / $totalVotes) > 0.5; 
        });

        if ($filtered->isEmpty()) {
            return response()->json(['message' => 'No facts met the voting threshold'], 404);
    }

    $texts = $filtered->pluck('text')->values();

    return response()->json(['facts' => $texts], 200);
}



}

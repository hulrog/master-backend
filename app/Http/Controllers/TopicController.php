<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Area;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function getAllTopics()
    {
        $topics = Topic::all();
        if ($topics->isEmpty()) {
            return response()->json(['message' => 'No topics found'], 404);
        }
        return response()->json(['message' => 'Topics retrieved successfully', 'topics' => $topics], 200);
    }
    
    public function createTopic(Request $request)
    {
        if (!$request->has('area_id') || $request->input('area_id') == 0) {
            $request->validate([
                'area_name' => 'required|string',
            ]);
            $area = Area::create([
                'name' => $request->input('area_name'),
            ]);
            $request->merge(['area_id' => $area->area_id]);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'area_id' => 'required|integer'
        ]);
        $topic = Topic::create($validatedData);
        return response()->json(['message' => 'Topic created successfully', 'topic' => $topic], 201);
    }

    public function getAllTopicsContainingLetters($letters)
    {
        $topics = Topic::where('name', 'like', "%$letters%")
            ->get();
        if ($topics->isEmpty()) {
            return response()->json(['message' => 'No topics found containing the given letters'], 404);
        }
        return response()->json(['message' => 'Topics retrieved successfully', 'topics' => $topics], 200);
    }
}

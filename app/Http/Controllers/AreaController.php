<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{

    public function getAllAreas()
    {
        $areas = Area::all();
        if(is_null($areas)) {
            return response()->json(['message' => 'No areas found'], 404);
        }
        return response()->json(['message'=> 'Areas retrieved successfully', 'areas' => $areas], 200);
    }

    public function createArea(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $area = Area::create($validatedData);
        return response()->json(['message' => 'Area created successfully', 'area' => $area], 201);
    }

    public function getAllAreasContainingLetters($letters)
    {
        $areas = Area::where('name', 'like', "%$letters%")
            ->get();
        if ($areas->isEmpty()) {
            return response()->json(['message' => 'No areas found containing the given letters'], 404);
        }
        return response()->json(['message' => 'Areas retrieved successfully', 'areas' => $areas], 200);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function testAi(Request $request)
    {
        $apiKey = env('GEMINI_API_KEY');
        $sentences = $request->input('sentences', []);

        if (empty($sentences) || !is_array($sentences)) {
            return response()->json(['error' => 'Please provide an array of sentences.'], 400);
        }

        $userPrompt = "Arrange the information within the following sentences into a coherent instructive paragraph. 
            Do not keep the sentence structure the same. 
            Don't infer any non-obvious information that is not provided within the sentences, 
            but make the text stylistically pleasant. :\n\n";
        foreach ($sentences as $index => $sentence) {
            $userPrompt .= ($index + 1) . ". " . trim($sentence) . "\n";
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $userPrompt]
                    ]
                ]
            ]
        ]);

        if (! $response->ok()) {
            return response()->json(['error' => 'Gemini API error', 'details' => $response->json()], $response->status());
        }

        $data = $response->json();
        $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response';

        return response()->json([
            'prompt' => $userPrompt,
            'response' => $reply,
            'data' => $data
        ]);
    }
}
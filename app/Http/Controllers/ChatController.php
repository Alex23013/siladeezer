<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth:api');
    }

    public function chat(Request $request)
    {
      $user = Auth::user();
      if (! $user){
        return response()->json([
          'status' => 'error',
          'message' => 'No user logged',
          'data'=> []
        ], 404);
      }

      $search = "Can you list 5 popular artist (only names)";

      switch ($request->option) {
        case "country":
          $search = $search." from ".$user->country;
          break;
        case "name":
          $search = $search." with the name similar to ".$user->name;
          break;
        default:
          return response()->json([
            'status' => 'error',
            'message' => "Supported options are: 'country' or 'name'",
            'data'=> []
          ], 404);
      }
  
      $data = Http::withHeaders([
                  'Content-Type' => 'application/json',
                  'Authorization' => 'Bearer '.env('OPENAI_API_KEY'),
                ])
                ->post("https://api.openai.com/v1/chat/completions", [
                  "model" => "gpt-3.5-turbo",
                  'messages' => [
                      [
                          "role" => "user",
                          "content" => $search
                      ]
                  ],
                  'temperature' => 0.5,
                  "max_tokens" => 200,
                  "top_p" => 1.0,
                  "frequency_penalty" => 0.52,
                  "presence_penalty" => 0.5,
                  "stop" => ["11."],
                ])
                ->json();

      return response()->json([
          'status' => 'success',
          'message' => '',
          'data'=> "Based in your ".$request->option. " we can recomend listen following artists :".$data['choices'][0]['message']['content']
        ]);
    }
}

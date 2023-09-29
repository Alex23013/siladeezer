<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Song;
use App\Models\Album;

class SongController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    /**
     * Display a listing of all songs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     
        $songs = Song::all();
        
        return response()->json([
            'status' => 'success',
            'message' => '',
            'data'=> $songs
            
        ]);    
    }

    /**
     * Store a newly created song in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255|unique:songs',
            'album_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Song not created because of the following errors',
                'data'=> [
                  'errors'=>  $validator->messages()
                ]
                
            ], 400);
        }
        $album = Album::find($request->album_id);
        if (!$album){
            return response()->json([
                'status' => 'error',
                'message' => 'Song not created because Album not found',
                'data'=> []
            ], 404);
        }

        $song = Song::create([
            'name' => $request->name,
            'album_id' => $album->id,
            'artist_id' => $album->artist->id,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Song created successfully',
            'data'=> $song
            
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $song = Song::find($id);
        if (!$song){
            return response()->json([
                'status' => 'error',
                'message' => 'Song not found',
                'data'=> []
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => '',
            'data'=> $song
        ]);  
    }

    public function update(Request $request){
        
        $validator = Validator::make($request->all(),[
            'name' => 'string|max:255',
            'song_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Song not updated because of the following errors',
                'data'=> [
                  'errors'=>  $validator->messages()
                ]
                
            ], 400);
        }
        $data = $request->all();
        $song = Song::find($request->song_id);
        if (!$song){
            return response()->json([
                'status' => 'error',
                'message' => 'Song not found',
                'data'=> []
            ], 404);
        }
        unset($data['song_id']);
        foreach ($data as $key => $value) {
            if ($value != '' && $data[$key]!= $song->$key) {
                $song->$key =$data[$key];
            }
        }
        $song->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Song successfully updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $song = Song::find($id);
        
        if (!$song){
            return response()->json([
                'status' => 'error',
                'message' => 'Song not found',
                'data'=> []
            ], 404);
        }

        Song::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Song with id '.$id.' destroyed',
            'data'=> []
        ]);  
        
    }
}

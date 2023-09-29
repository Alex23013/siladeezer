<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Artist;

class ArtistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    /**
     * Display a listing of all artists.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     
        $artists = Artist::all();
        
        return response()->json([
            'status' => 'success',
            'message' => '',
            'data'=> $artists
            
        ]);    
    }

    /**
     * Store a newly created artist in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'genre' => 'required|string|max:50',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Artist not created because of the following errors',
                'data'=> [
                  'errors'=>  $validator->messages()
                ]
                
            ], 400);
        }
        $artist = Artist::create([
            'name' => $request->name,
            'genre' => $request->genre,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Artist created successfully',
            'data'=> $artist
            
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
        $artist = Artist::with('albums')->find($id);
        if (!$artist){
            return response()->json([
                'status' => 'error',
                'message' => 'Artist not found',
                'data'=> []
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => '',
            'data'=> $artist
            
        ]);  
    }

    public function update(Request $request){
        
        $validator = Validator::make($request->all(),[
            'name' => 'string|max:255|unique:artists',
            'genre' => 'string|max:50',
            'artist_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Artist not updated because of the following errors',
                'data'=> [
                  'errors'=>  $validator->messages()
                ]
                
            ], 400);
        }
        $data = $request->all();
        $artist = Artist::find($request->artist_id);
        if (!$artist){
            return response()->json([
                'status' => 'error',
                'message' => 'Artist not found',
                'data'=> []
            ], 404);
        }
        unset($data['artist_id']);
        foreach ($data as $key => $value) {
            if ($value != '' && $data[$key]!= $artist->$key) {
                $artist->$key =$data[$key];
            }
        }
        $artist->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Artist successfully updated'
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
        $artist = Artist::find($id);
        
        if (!$artist){
            return response()->json([
                'status' => 'error',
                'message' => 'Artist not found',
                'data'=> []
            ], 404);
        }

        Artist::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Artist with id '.$id.' destroyed',
            'data'=> []
        ]);  
        
    }
}

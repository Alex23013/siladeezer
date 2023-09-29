<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Album;

class AlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    /**
     * Display a listing of all albums.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     
        $albums = Album::all();
        
        return response()->json([
            'status' => 'success',
            'message' => '',
            'data'=> $albums
            
        ]);    
    }

    /**
     * Store a newly created album in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255|unique:albums',
            'artist_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Album not created because of the following errors',
                'data'=> [
                  'errors'=>  $validator->messages()
                ]
                
            ], 400);
        }
        $album = Album::create([
            'name' => $request->name,
            'artist_id' => $request->artist_id,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Album created successfully',
            'data'=> $album
            
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
        $album = Album::with('songs')->find($id);
        if (!$album){
            return response()->json([
                'status' => 'error',
                'message' => 'Album not found',
                'data'=> []
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => '',
            'data'=> $album
        ]);  
    }

    public function update(Request $request){
        
        $validator = Validator::make($request->all(),[
            'name' => 'string|max:255',
            'album_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Album not updated because of the following errors',
                'data'=> [
                  'errors'=>  $validator->messages()
                ]
                
            ], 400);
        }
        $data = $request->all();
        $album = Album::find($request->album_id);
        if (!$album){
            return response()->json([
                'status' => 'error',
                'message' => 'Album not found',
                'data'=> []
            ], 404);
        }
        unset($data['album_id']);
        foreach ($data as $key => $value) {
            if ($value != '' && $data[$key]!= $album->$key) {
                $album->$key =$data[$key];
            }
        }
        $album->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Album successfully updated'
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
        $album = Album::find($id);
        
        if (!$album){
            return response()->json([
                'status' => 'error',
                'message' => 'Album not found',
                'data'=> []
            ], 404);
        }

        Album::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Album with id '.$id.' destroyed',
            'data'=> []
        ]);  
        
    }
}

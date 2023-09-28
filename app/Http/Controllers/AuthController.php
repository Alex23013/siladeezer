<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;
use App\Models\User;


class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'message' => 'User successfully logged',
                'data' => [
                    'token' => $token,
                ]
            ]);

    }

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'confirm' => 'required|string|min:6|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not created because of the following errors',
                'data'=> [
                  'errors'=>  $validator->messages()
                ]
                
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'country' => $request->country,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successful logout',
        ]);
    }

    public function me()
    {
        if (Auth::user()){
            return response()->json([
                'status' => 'success',
                'message' => '',
                'data'=> [
                    'user' => Auth::user(),
                ]
                
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'No user logged',
            'data'=> []
        ], 404);
        
    }

    public function update_user(Request $request){
        
        $validator = Validator::make($request->all(),[
            'name' => 'string|max:255',
            'country' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users',
            'password' => 'string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not updated because of the following errors',
                'data'=> [
                  'errors'=>  $validator->messages()
                ]
                
            ], 400);
        }
        $data = $request->all();
        $user = Auth::user();

        foreach ($data as $key => $value) {
            if ($value != '' && $data[$key]!= $user->$key) {
                if ($key != 'password') {
                    $user->$key =$data[$key];
                }
            }
        }
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User successfully updated'
        ]);
    }

}

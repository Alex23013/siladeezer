<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SongController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::get('me', 'me');
    Route::post('update_user', 'update_user');
});

Route::controller(ArtistController::class)->group(function () {
    Route::get('artist', 'index');
    Route::post('artist/store', 'store');
    Route::get('artist/{id}', 'show')->where(['id' => '[0-9]+']);
    Route::post('artist/update', 'update');
    Route::get('artist/destroy/{id}', 'destroy')->where(['id' => '[0-9]+']);
});

Route::controller(AlbumController::class)->group(function () {
    Route::get('album', 'index');
    Route::post('album/store', 'store');
    Route::get('album/{id}', 'show')->where(['id' => '[0-9]+']);
    Route::post('album/update', 'update');
    Route::get('album/destroy/{id}', 'destroy')->where(['id' => '[0-9]+']);
});

Route::controller(SongController::class)->group(function () {
    Route::get('song', 'index');
    Route::post('song/store', 'store');
    Route::get('song/{id}', 'show')->where(['id' => '[0-9]+']);
    Route::post('song/update', 'update');
    Route::get('song/destroy/{id}', 'destroy')->where(['id' => '[0-9]+']);
});

Route::controller(chatController::class)->group(function () {
    Route::post('chat', 'chat');
});
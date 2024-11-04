<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PreferenceController;
use Illuminate\Support\Facades\DB;

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

Route::middleware('auth:sanctum')->group(function () {
});


    //User Details
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/resetpassword', [AuthController::class, 'resetPassword']);

    //For Articles
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('articles/{id}', [ArticleController::class, 'show']);

    Route::post('/preferences', [PreferenceController::class, 'setPreferences']);
    Route::get('/preferences', [PreferenceController::class, 'getPreferences']);
    Route::get('/personalized-feed', [PreferenceController::class, 'personalizedFeed']);


Route::post('/fetch-articles', function () {
    Artisan::call('fetch:articles');
    return response()->json(['message' => 'Articles fetched successfully.']);
});

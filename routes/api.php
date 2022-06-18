<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Resources\ColorResource;
use App\Models\Colors;

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

Route::post('login',[AuthController::class, 'login']);
Route::post('register',[AuthController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('profile', function(Request $request) {
        return $request->user();
        // return auth()->user();
    });

    Route::patch('save_profile', [AuthController::class, 'save_auth_profile']);
    Route::get('colors', function(Request $request) {
        return ColorResource::collection(Colors::all());
    });

    // API route for logout user
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Route Not Found.'], 404);
});

<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);



Route::group([
    "middleware" => ["auth:api"]
],function(){
    Route::get('/job/search',[JobController::class,'search']);
    Route::get('/job/apply',[JobController::class,'applyForJob']);
    Route::get('/job/list',[JobController::class,'Joblisting']);
    Route::resource('/job',JobController::class)->middleware('isEmployer');
    Route::get('logout',[AuthController::class,'logout']);
});

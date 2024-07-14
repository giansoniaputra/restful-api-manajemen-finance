<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::post('/users/register', [AuthController::class, 'register']);
Route::post('/users/login', [AuthController::class, 'login']);
Route::post('/users/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('/users/me', [AuthController::class, 'me'])->middleware('auth:api');
Route::put('/users/update', [AuthController::class, 'update'])->middleware('auth:api');

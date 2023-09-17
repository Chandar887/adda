<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

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

Route::controller(ApiController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});


// Auth middleware
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', function (Request $request) {
        return $request->user();
    });
});
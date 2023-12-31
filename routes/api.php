<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AuthController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/verify-otp', 'verifyOtp');
    Route::post('/forgot-password', 'forgotPasswordMail');
    Route::post('/verify-token', 'verifyToken');
    Route::post('/reset-password', 'resetPassword');
});


// Auth middleware
Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(ApiController::class)->group(function () {
        Route::get('/dashboard', 'index');
        Route::get('/logout', 'logout');
        Route::get('/get-banner-images', 'getBannerImages');
        Route::get('/get-category-list', 'getCategoryList');
        Route::get('/get-product-ratings', 'getProductRatings');
        Route::post('/save-product-rating', 'saveProductRating');
        Route::get('/get-products', 'getProducts');
        Route::get('/get-all-products', 'getAllProducts');
        Route::get('/get-orders', 'getOrders');
        Route::post('/save-order', 'saveOrder');
        Route::get('/get-favourites', 'getFavourites');
        Route::post('/save-favourites', 'saveFavourites');
        Route::delete('/delete-favourites', 'deleteFavourites');
    });
});
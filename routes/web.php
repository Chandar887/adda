<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    dd("Cache-Clear");
});

Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    dd("Config-cache");
});

Route::get('/config-clear', function () {
    Artisan::call('config:clear');
    dd("Config-clear");
});


Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/admin-login', [App\Http\Controllers\HomeController::class, 'login'])->name('storelogin');
Route::match(['GET', 'POST'], 'logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {
    Route::group(
        ['namespace' => 'App\Http\Controllers', 'prefix' => 'admin', 'as' => 'admin.'],
        function () {
            Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
            //Banner
            Route::get('/add-banner', 'HomeController@banner')->name('addbanner');
            Route::post('/store-banner', 'HomeController@storeBanner')->name('storebanner');
            Route::get('/edit-banner/{id}', 'HomeController@editBanner')->name('editbanner');
            Route::post('/update-banner/{id}', 'HomeController@updateBanner')->name('updatebanner');

            //Category
            Route::resource('categories', 'CategoryController');

        });
    });

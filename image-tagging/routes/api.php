<?php

use App\Http\Controllers\ImagesController;
use App\Http\Controllers\TagsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
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

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::resource('image', ImagesController::class);
    Route::post('/image/{image}/tag', [TagsController::class,'store_tag']);
});

Route::prefix('v1')->group(function () {

    Route::post('/signup', [UsersController::class, 'signup']);

    Route::get('/images/all', [ImagesController::class, 'show_all']);
});


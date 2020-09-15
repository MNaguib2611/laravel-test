<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;


// TODO: Only authenticated users have access.



Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('register', [AuthController::class,'register']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::get('user-profile', [AuthController::class,'userProfile']);
});



Route::group([
    'middleware' => 'auth:api',

], function ($router) {

    
    Route::apiResource('posts', PostController::class)->only('index', 'store', 'show');
    Route::apiResource('posts/{post}/comments', CommentController::class)->only('store');
    Route::get('notifications', NotificationController::class);

});











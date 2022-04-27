<?php

use App\Http\Controllers\Api\v1\auth\LoginController;
use App\Http\Controllers\Api\v1\auth\LogoutController;
use App\Http\Controllers\Api\v1\auth\RegisterController;
use App\Http\Controllers\Api\v1\posts\DestroyController;
use App\Http\Controllers\Api\v1\posts\FeedController;
use App\Http\Controllers\Api\v1\posts\LikesController;
use App\Http\Controllers\Api\v1\posts\ReactController;
use App\Http\Controllers\Api\v1\posts\StoreController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function (){
    /* Auth */
    Route::prefix('auth')->group(function () {
        Route::post('register', RegisterController::class);
        Route::post('login', LoginController::class);
        Route::post('logout', LogoutController::class);
    });

    /* network feed */
    Route::get('posts/feed', FeedController::class)->name('feed');

    Route::group(['prefix' => 'posts', 'middleware' => ['auth:api']], function () {
        /* create post */
        Route::post('', StoreController::class)->name('posts.store');
        /* delete post */
        Route::delete('{post}', DestroyController::class)->name('posts.delete');
        /* react to post */
        Route::get('{postUUID}/react', ReactController::class)->name('posts.react');
        /* get post's reacters */
        Route::get('{post}/likes', LikesController::class)->name('posts.likes');
    });
});

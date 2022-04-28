<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostsController;
use Illuminate\Support\Facades\Route;

/* Auth */
Route::controller(AuthController::class)
    ->prefix('auth')
    ->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->middleware('auth:api');
        Route::post('/register', 'register');
    });

/* network feed */
Route::get('posts/feed', [PostsController::class, 'index'])->name('feed');

Route::controller(PostsController::class)
    ->prefix('posts')
    ->middleware('auth:api')
    ->group(function () {
        /* create post */
        Route::post('', 'store')->name('posts.store');
        /* delete post */
        Route::delete('{post}', 'destroy')->name('posts.delete')->middleware('can:delete,post');
        /* react to post */
        Route::get('{post}/react', 'react')->name('posts.react');
        /* get post's reacters */
        Route::get('{post}/likes', 'likes')->name('posts.likes');
    });

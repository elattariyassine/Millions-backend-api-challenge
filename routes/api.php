<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostsController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)
    ->prefix('auth')
    ->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->middleware('auth:api');
        Route::post('/register', 'register');
    });

Route::get('posts/feed', [PostsController::class, 'index'])->name('feed');

Route::controller(PostsController::class)
    ->prefix('posts')
    ->as('posts.')
    ->middleware('auth:api')
    ->group(function () {
        Route::post('/', 'store')->name('store');
        Route::post('{post}/react', 'react')->name('react');
        Route::delete('{post}', 'destroy')->name('delete')->middleware('can:delete,post');
        Route::get('{post}/likes', 'likes')->name('likes');
    });

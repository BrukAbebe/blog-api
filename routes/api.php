<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login'); 

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('logout');

Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [PostController::class, 'store'])
        ->name('posts.store');
    Route::patch('/posts/{post}', [PostController::class, 'update'])
        ->name('posts.update');
    Route::get('/user/posts', [PostController::class, 'userPosts'])
        ->name('posts.user');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->name('posts.destroy');
});
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
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
Route::get('/posts/{post}', [PostController::class, 'show'])
    ->name('posts.show');
Route::get('/posts/{post}/comments', [CommentController::class, 'index'])
    ->name('comments.index');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [PostController::class, 'store'])
        ->name('posts.store');
    Route::patch('/posts/{post}', [PostController::class, 'update'])
        ->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->name('posts.destroy');
    Route::get('/user/posts', [PostController::class, 'userPosts'])
        ->name('posts.user');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    Route::post('/posts/{post}/likes', [LikeController::class, 'store'])
        ->name('likes.store');
    Route::delete('/posts/{post}/likes', [LikeController::class, 'destroy'])
        ->name('likes.destroy');
});
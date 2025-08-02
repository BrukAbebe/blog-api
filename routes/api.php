<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/admin/register', [AuthController::class, 'registerAdmin'])->middleware('role:admin,sanctum');
    
    Route::get('/posts', [PostController::class, 'index'])->middleware('role:admin|author|viewer,sanctum');
    Route::post('/posts', [PostController::class, 'store'])->middleware('role:admin|author,sanctum');
    Route::get('/user/posts', [PostController::class, 'userPosts'])->middleware('role:admin|author,sanctum');
    Route::get('/posts/{post}', [PostController::class, 'show'])->middleware('role:admin|author|viewer,sanctum');
    Route::patch('/posts/{post}', [PostController::class, 'update'])->middleware(['role:admin|author,sanctum', 'ownership:post']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware(['role:admin|author,sanctum', 'ownership:post']);
    
    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->middleware('role:admin|author|viewer,sanctum');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->middleware('role:admin|author|viewer,sanctum');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])->middleware(['role:admin|author,sanctum', 'ownership:comment']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware(['role:admin|author,sanctum', 'ownership:comment']);
    
    Route::post('/posts/{post}/likes', [LikeController::class, 'store'])->middleware('role:admin|author|viewer,sanctum');
    Route::delete('/posts/{post}/likes', [LikeController::class, 'destroy'])->middleware('role:admin|author|viewer,sanctum');
});
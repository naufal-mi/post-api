<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/create', function () {
    return view('create');
});

// Routing Post
Route::post('/posts', [PostController::class, 'store']);
Route::patch('/list/{id}', [PostController::class, 'update']);
Route::get('/list', [PostController::class, 'index']);
Route::delete('/list/{id}', [PostController::class, 'destroy']);
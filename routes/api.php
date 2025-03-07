<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Tasks
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tasks', [TaskController::class, 'store']); // Create
    Route::get('/tasks', [TaskController::class, 'index']); // List
    Route::put('/tasks/{task}', [TaskController::class, 'update']); // Update
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']); // Delete
});
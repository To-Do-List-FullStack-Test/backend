<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NotificationController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('unread-count', [NotificationController::class, 'unreadCount']);
        Route::post('mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::patch('{id}/read', [NotificationController::class, 'markAsRead']);
    });
});

Route::middleware(['auth:api', 'task.access'])->group(function () {
    Route::apiResource('tasks', TaskController::class);
    Route::patch('/tasks/{task}/complete', [TaskController::class, 'markCompleted']);
});

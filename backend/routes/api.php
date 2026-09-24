<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PersonnelController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public & Backoffice Endpoints
Route::get('/stats', [DashboardController::class, 'stats']);

// Posts
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::post('/posts', [PostController::class, 'store']);
Route::put('/posts/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);

// Personnel
Route::get('/personnel', [PersonnelController::class, 'index']);
Route::get('/personnel/{id}', [PersonnelController::class, 'show']);
Route::post('/personnel', [PersonnelController::class, 'store']);
Route::put('/personnel/{id}', [PersonnelController::class, 'update']);
Route::delete('/personnel/{id}', [PersonnelController::class, 'destroy']);

// Uploads
Route::post('/upload', [UploadController::class, 'upload']);

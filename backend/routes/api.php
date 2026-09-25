<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FacultyHistoryController;
use App\Http\Controllers\Api\ItaController;
use App\Http\Controllers\Api\PersonnelController;
use App\Http\Controllers\Api\PhilosophyController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RegulationController;
use App\Http\Controllers\Api\CarouselSlideController;
use App\Http\Controllers\Api\CommitteeMemberController;
use App\Http\Controllers\Api\CurriculumController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\ExecutiveController;
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
Route::post('/personnel/reorder', [PersonnelController::class, 'reorder']);
Route::put('/personnel/{id}', [PersonnelController::class, 'update']);
Route::delete('/personnel/{id}', [PersonnelController::class, 'destroy']);

// Departments
Route::get('/departments', [DepartmentController::class, 'index']);
Route::post('/departments', [DepartmentController::class, 'store']);
Route::post('/departments/reorder', [DepartmentController::class, 'reorder']);
Route::post('/departments/{id}/set-head', [DepartmentController::class, 'setHead']);
Route::put('/departments/{id}', [DepartmentController::class, 'update']);
Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

// Uploads
Route::post('/upload', [UploadController::class, 'upload']);

// Faculty History
Route::get('/history', [FacultyHistoryController::class, 'index']);
Route::post('/history', [FacultyHistoryController::class, 'store']);
Route::put('/history/{id}', [FacultyHistoryController::class, 'update']);
Route::delete('/history/{id}', [FacultyHistoryController::class, 'destroy']);

// Philosophy & Vision
Route::get('/philosophy', [PhilosophyController::class, 'index']);
Route::post('/philosophy', [PhilosophyController::class, 'update']);
Route::post('/philosophy/missions', [PhilosophyController::class, 'storeMission']);
Route::put('/philosophy/missions/{id}', [PhilosophyController::class, 'updateMission']);
Route::delete('/philosophy/missions/{id}', [PhilosophyController::class, 'destroyMission']);

// ITA Items & Years
Route::get('/ita-years', [ItaController::class, 'getYears']);
Route::post('/ita-years', [ItaController::class, 'storeYear']);
Route::put('/ita-years/{id}', [ItaController::class, 'updateYear']);
Route::delete('/ita-years/{id}', [ItaController::class, 'destroyYear']);

Route::get('/ita', [ItaController::class, 'index']);
Route::post('/ita/reorder', [ItaController::class, 'reorder']);
Route::post('/ita', [ItaController::class, 'store']);
Route::put('/ita/{id}', [ItaController::class, 'update']);
Route::delete('/ita/{id}', [ItaController::class, 'destroy']);

// Regulations
Route::get('/regulations', [RegulationController::class, 'index']);
Route::post('/regulations', [RegulationController::class, 'store']);
Route::put('/regulations/{id}', [RegulationController::class, 'update']);
Route::delete('/regulations/{id}', [RegulationController::class, 'destroy']);
Route::get('/regulation-categories', [RegulationController::class, 'getCategories']);
Route::post('/regulation-categories', [RegulationController::class, 'storeCategory']);
Route::put('/regulation-categories/{id}', [RegulationController::class, 'updateCategory']);
Route::delete('/regulation-categories/{id}', [RegulationController::class, 'destroyCategory']);

// Carousel Slides
Route::get('/carousel-slides', [CarouselSlideController::class, 'index']);
Route::post('/carousel-slides', [CarouselSlideController::class, 'store']);
Route::post('/carousel-slides/reorder', [CarouselSlideController::class, 'reorder']);
Route::put('/carousel-slides/{id}', [CarouselSlideController::class, 'update']);
Route::delete('/carousel-slides/{id}', [CarouselSlideController::class, 'destroy']);

// Committee Members
Route::get('/committee-members', [CommitteeMemberController::class, 'index']);
Route::post('/committee-members', [CommitteeMemberController::class, 'store']);
Route::post('/committee-members/reorder', [CommitteeMemberController::class, 'reorder']);
Route::put('/committee-members/{id}', [CommitteeMemberController::class, 'update']);
Route::delete('/committee-members/{id}', [CommitteeMemberController::class, 'destroy']);

// Executives (Categories & Members)
Route::get('/executives', [ExecutiveController::class, 'index']);
Route::post('/executive-categories', [ExecutiveController::class, 'storeCategory']);
Route::post('/executive-categories/reorder', [ExecutiveController::class, 'reorderCategories']);
Route::put('/executive-categories/{id}', [ExecutiveController::class, 'updateCategory']);
Route::delete('/executive-categories/{id}', [ExecutiveController::class, 'destroyCategory']);

Route::post('/executive-members', [ExecutiveController::class, 'storeMember']);
Route::post('/executive-members/reorder', [ExecutiveController::class, 'reorderMembers']);
Route::put('/executive-members/{id}', [ExecutiveController::class, 'updateMember']);
Route::delete('/executive-members/{id}', [ExecutiveController::class, 'destroyMember']);

// Curricula (หลักสูตร)
Route::get('/curricula', [CurriculumController::class, 'index']);
Route::get('/curricula/{idOrSlug}', [CurriculumController::class, 'show']);
Route::post('/curricula', [CurriculumController::class, 'store']);
Route::post('/curricula/reorder', [CurriculumController::class, 'reorder']);
Route::put('/curricula/{id}', [CurriculumController::class, 'update']);
Route::delete('/curricula/{id}', [CurriculumController::class, 'destroy']);





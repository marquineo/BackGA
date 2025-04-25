<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrainingBlockController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\RoleController;

//USERS
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}/indexUserByID', [UserController::class, 'indexUserByID']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{trainer_id}/clientes', [UserController::class, 'showByTrainer_id']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
    Route::post('/login', [UserController::class, 'checkUserLogin']);
});

//TRAINIGN-BLOCKS
Route::prefix('training-blocks')->group(function () {
    Route::get('/', [TrainingBlockController::class, 'index']);
    Route::post('/', [TrainingBlockController::class, 'store']);
    Route::get('/{id}', [TrainingBlockController::class, 'show']);
    Route::put('/{id}', [TrainingBlockController::class, 'update']);
    Route::delete('/{id}', [TrainingBlockController::class, 'destroy']);
});

//WORKOUTS
Route::prefix('workouts')->group(function () {
    Route::get('/', [WorkoutController::class, 'index']);
    Route::post('/', [WorkoutController::class, 'store']);
    Route::get('/{id}', [WorkoutController::class, 'show']);
    Route::put('/{id}', [WorkoutController::class, 'update']);
    Route::delete('/{id}', [WorkoutController::class, 'destroy']);
});

//EXERCISES
Route::prefix('exercises')->group(function () {
    Route::get('/', [ExerciseController::class, 'index']);
    Route::post('/', [ExerciseController::class, 'store']);
    Route::get('/{id}', [ExerciseController::class, 'show']);
    Route::put('/{id}', [ExerciseController::class, 'update']);
    Route::delete('/{id}', [ExerciseController::class, 'destroy']);
});

//PROGRESS
Route::prefix('progress')->group(function () {
    Route::get('/', [ProgressController::class, 'index']);
    Route::post('/', [ProgressController::class, 'store']);
    Route::get('/{id}', [ProgressController::class, 'show']);
    Route::put('/{id}', [ProgressController::class, 'update']);
    Route::delete('/{id}', [ProgressController::class, 'destroy']);
});

//ROLES
Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/', [RoleController::class, 'store']);
    Route::get('/{id}', [RoleController::class, 'show']);
    Route::put('/{id}', [RoleController::class, 'update']);
    Route::delete('/{id}', [RoleController::class, 'destroy']);
});

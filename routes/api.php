<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TrainingBlockController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RutinaEntrenamientoController;

//USERS
Route::prefix('users')->group(function () {
    Route::get('/', [UsuarioController::class, 'index']);
    Route::get('/{id}/indexUserByID', [UsuarioController::class, 'indexUserByID']);
    Route::post('/', [UsuarioController::class, 'store']);
    Route::put('/{id}', [UsuarioController::class, 'update']);
    Route::delete('/{id}', [UsuarioController::class, 'destroy']);
    Route::post('/login', [UsuarioController::class, 'checkUserLogin']);


    //clientes
    Route::post('/registrar/cliente', [UsuarioController::class, 'registrarCliente']);
    Route::get('/{trainer_id}/clientes', [UsuarioController::class, 'showByTrainer_id']);

    //entrenadores
    Route::post('/registrar/entrenador', [UsuarioController::class, 'registrarEntrenador']);
    Route::get('/{id}/indexEntrenadorByID', [UsuarioController::class, 'indexEntrenadorByID']);
    Route::post('/{usuario_id}/actualizar/entrenador', [UsuarioController::class, 'actualizarEntrenadorPorUsuarioId']);


    //administradores
    Route::post('/registrar/administrador', [UsuarioController::class, 'registrarAdministrador']);
});
//RUTINAS
Route::prefix('rutinas')->group(function () { //CAMBIAR EL CONTROLADOR Y AÑADIR EL MODELO EN USE
    Route::get('/{clienteId}', [RutinaEntrenamientoController::class, 'showRutinaByClienteId']);
    Route::put('/cliente/{clienteId}', [RutinaEntrenamientoController::class, 'updateRutinaByClienteId']);
    Route::post('/cliente/{clienteId}/ejercicio', [RutinaEntrenamientoController::class, 'storeRutinaByClienteId']);
    Route::delete('/cliente/{clienteId}/ejercicio/{ejercicioId}', [RutinaEntrenamientoController::class, 'deleteRutinaByClienteId']);
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

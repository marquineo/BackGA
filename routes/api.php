<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TrainingBlockController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RutinaEntrenamientoController;
use App\Http\Controllers\ProgresoController;
use App\Http\Controllers\ProgresoFisicoController;

Route::get('/test-cors', function () {
    \Log::info('Test CORS route hit!');
    return response()->json(['message' => 'CORS test OK']);
});

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
    Route::get('clientes/{usuarioId}/getClienteByUsuarioId', [UsuarioController::class, 'getClienteByUsuarioId']);
    Route::post('/clientes/atletas/{id}/actualizar', [UsuarioController::class, 'actualizarAtleta']);
    Route::delete('/clientes/atletas/{id}/eliminar', [UsuarioController::class, 'eliminar'])->name('atleta.eliminar');



    //entrenadores
    Route::post('/registrar/entrenador', [UsuarioController::class, 'registrarEntrenador']);
    Route::get('/{id}/indexEntrenadorByID', [UsuarioController::class, 'indexEntrenadorByID']);
    Route::post('/{usuario_id}/actualizar/entrenador', [UsuarioController::class, 'actualizarEntrenadorPorUsuarioId']);
    Route::get('/entrenadores', [UsuarioController::class, 'getAllEntrenadores']);
    Route::delete('/entrenador/{id}/eliminar', [UsuarioController::class, 'deleteEntrenador']);
    

    //administradores
    Route::post('/registrar/administrador', [UsuarioController::class, 'registrarAdministrador']);
});

Route::prefix('progresos')->group(function () {

    Route::get('/{clienteId}', [ProgresoFisicoController::class, 'getProgresosPorCliente']);

    Route::get('/clientes/{clienteId}/progresos', [ProgresoFisicoController::class, 'obtenerProgresos']);
    Route::delete('/eliminar/{id}', [ProgresoFisicoController::class, 'eliminarProgreso']);

    Route::post('/guardar/{clienteId}', [ProgresoFisicoController::class, 'guardarProgreso']);
});
//RUTINAS
Route::prefix('rutinas')->group(function () {
    Route::get('/{clienteId}', [RutinaEntrenamientoController::class, 'showRutinasByClienteId']);
    Route::put('/cliente/{clienteId}', [RutinaEntrenamientoController::class, 'guardarRutinas']);
    Route::delete('/cliente/{clienteId}/ejercicio/{ejercicioId}', [RutinaEntrenamientoController::class, 'deleteRutinaByClienteId']);
    Route::post('/clientes/{clienteId}/rutinas/eliminar', [RutinaEntrenamientoController::class, 'eliminarRutinas']);
    //dashboard-entrenador
    Route::get('/clientes/{clienteId}/rutinas-con-ejercicios', [RutinaEntrenamientoController::class, 'getRutinasPorClienteConEjercicios']);
    //dashboard-cliente
    Route::get('entrenamientos/{clienteId}', [RutinaEntrenamientoController::class, 'getEntrenamientoPorFecha']);
    Route::get('/fechas-con-entrenamiento/{clienteId}', [RutinaEntrenamientoController::class, 'getFechasConEntrenamiento']);
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

//ROLES
Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/', [RoleController::class, 'store']);
    Route::get('/{id}', [RoleController::class, 'show']);
    Route::put('/{id}', [RoleController::class, 'update']);
    Route::delete('/{id}', [RoleController::class, 'destroy']);
});
Route::options('/test-cors', function () {
    return response()->json(['message' => 'CORS working']);
});

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RutinaEntrenamiento;
use App\Models\EjercicioRutina;

class RutinaEntrenamientoController extends Controller
{
    public function showRutinasByClienteId($clienteId)
    {
        $rutinas = RutinaEntrenamiento::with('ejercicios')
            ->where('cliente_id', $clienteId)
            ->get();

        if ($rutinas->isEmpty()) {
            return response()->json(['message' => 'No se encontraron rutinas para este cliente'], 404);
        }

        return response()->json($rutinas);
    }


    public function guardarRutinas(Request $request, $clienteId)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'duracion_semana' => 'required|integer|min:1',
            'ejercicios' => 'required|array',
            'ejercicios.*.nombre_ejercicio' => 'required|string',
            'ejercicios.*.series' => 'required|integer|min:1',
            'ejercicios.*.repeticiones' => 'required|integer|min:1',
            'ejercicios.*.descanso_segundos' => 'required|integer|min:1',
            'ejercicios.*.dia_semana' => 'required|date',
            'ejercicios.*.orden' => 'required|integer|min:1',
            'ejercicios.*.rpe' => 'nullable|integer|min:1|max:10',
        ]);

        // Buscar o crear la rutina
        $rutina = RutinaEntrenamiento::updateOrCreate(
            [
                'cliente_id' => $clienteId,
                'nombre' => $data['nombre']
            ],
            [
                'descripcion' => $data['descripcion'],
                'duracion_semana' => $data['duracion_semana']
            ]
        );

        // Borrar ejercicios anteriores si existían
        $rutina->ejercicios()->delete();

        // Crear los nuevos ejercicios
        foreach ($data['ejercicios'] as $ej) {
            $rutina->ejercicios()->create($ej);
        }

        return response()->json([
            'message' => 'Rutina guardada correctamente',
            'rutina' => $rutina->load('ejercicios')
        ]);
        /**
         * $email = ['name' => 'Tu Nombre']
         */
    }

    // ✅ DELETE: Eliminar ejercicio específico por ID
    public function eliminarRutinas(Request $request, $clienteId)
    {
        $request->validate([
            'nombres' => 'required|array',
            'nombres.*' => 'string'
        ]);

        $nombres = $request->input('nombres');

        RutinaEntrenamiento::where('cliente_id', $clienteId)
            ->whereIn('nombre', $nombres)
            ->delete();

        return response()->json(['message' => 'Rutinas eliminadas correctamente']);
    }
    //dashboard-entrenador
    public function getRutinasPorClienteConEjercicios($clienteId)
    {
        $rutinas = RutinaEntrenamiento::with('ejercicios')
            ->where('cliente_id', $clienteId)
            ->get();

        return response()->json($rutinas);
    }

    public function getEntrenamientoPorFecha(Request $request, $clienteId)
{
    $fecha = $request->query('fecha'); // formato esperado: 'YYYY-MM-DD'

    // Buscamos todas las rutinas del cliente
    $rutinas = RutinaEntrenamiento::with(['ejercicios' => function ($query) use ($fecha) {
        $query->where('dia_semana', $fecha);
    }])->where('cliente_id', $clienteId)->get();

    // Filtramos solo las rutinas que tienen ejercicios ese día
    $rutinasConEjercicios = $rutinas->filter(function ($rutina) {
        return $rutina->ejercicios->isNotEmpty();
    });

    if ($rutinasConEjercicios->isEmpty()) {
        return response()->json(['message' => 'No hay entrenamiento para esta fecha'], 404);
    }

    return response()->json($rutinasConEjercicios->values()); // devuelve solo las rutinas activas ese día
}

public function getFechasConEntrenamiento($clienteId)
{
    $fechas = EjercicioRutina::whereHas('rutina', function ($q) use ($clienteId) {
        $q->where('cliente_id', $clienteId);
    })->pluck('dia_semana')->unique();

    return response()->json($fechas);
}


}

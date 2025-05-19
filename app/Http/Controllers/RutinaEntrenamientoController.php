<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RutinaEntrenamiento;
use App\Models\EjercicioRutina;

class RutinaEntrenamientoController extends Controller
{
    // ✅ GET: Mostrar rutina y sus ejercicios por cliente
    public function showRutinaByClienteId($clienteId)
    {
        $rutina = RutinaEntrenamiento::with('ejercicios')
            ->where('cliente_id', $clienteId)
            ->first();

        if (!$rutina) {
            return response()->json(['message' => 'No se encontró rutina para este cliente'], 404);
        }

        return response()->json($rutina);
    }

    // ✅ PUT: Actualizar la rutina general del cliente
    public function updateRutinaByClienteId(Request $request, $clienteId)
    {
        $request->validate([
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'duracion_semana' => 'required|integer|min:1'
        ]);

        $rutina = RutinaEntrenamiento::where('cliente_id', $clienteId)->first();

        if (!$rutina) {
            return response()->json(['message' => 'Rutina no encontrada'], 404);
        }

        $rutina->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'duracion_semana' => $request->duracion_semana
        ]);

        return response()->json(['message' => 'Rutina actualizada correctamente', 'rutina' => $rutina]);
    }

    // ✅ POST: Agregar un nuevo ejercicio a la rutina del cliente
    public function storeRutinaByClienteId(Request $request, $clienteId)
    {
        $request->validate([
            'nombre_ejercicio' => 'required|string',
            'repeticiones' => 'required|integer|min:1',
            'series' => 'required|integer|min:1',
            'dia_semana' => 'required|integer|min:0|max:6',
            'descanso_segundos' => 'required|integer|min:0',
            'orden' => 'required|integer|min:1'
        ]);

        $rutina = RutinaEntrenamiento::where('cliente_id', $clienteId)->first();

        if (!$rutina) {
            return response()->json(['message' => 'Rutina no encontrada para este cliente'], 404);
        }

        $ejercicio = new EjercicioRutina($request->all());
        $ejercicio->rutina_id = $rutina->id;
        $ejercicio->save();

        return response()->json(['message' => 'Ejercicio agregado correctamente', 'ejercicio' => $ejercicio]);
    }

    // ✅ DELETE: Eliminar ejercicio específico por ID
    public function deleteRutinaByClienteId($clienteId, $ejercicioId)
    {
        $rutina = RutinaEntrenamiento::where('cliente_id', $clienteId)->first();

        if (!$rutina) {
            return response()->json(['message' => 'Rutina no encontrada para este cliente'], 404);
        }

        $ejercicio = EjercicioRutina::where('rutina_id', $rutina->id)
            ->where('id', $ejercicioId)
            ->first();

        if (!$ejercicio) {
            return response()->json(['message' => 'Ejercicio no encontrado'], 404);
        }

        $ejercicio->delete();

        return response()->json(['message' => 'Ejercicio eliminado correctamente']);
    }
}

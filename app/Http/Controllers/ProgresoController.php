<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroProgreso;

class ProgresoController extends Controller
{
    public function getProgreso($clienteId)
    {
        // Obtener los registros de progreso ordenados por fecha
        $progresos = RegistroProgreso::where('cliente_id', $clienteId)
            ->orderBy('fecha', 'asc')
            ->get(['fecha', 'peso', 'grasa_corporal', 'repeticiones', 'tiempo_entrenamiento']);

        return response()->json($progresos);
    }
}

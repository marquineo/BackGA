<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgresoFisico;

class ProgresoFisicoController extends Controller
{
    // Obtener todos los registros de progreso de un cliente
    public function getProgresosPorCliente($clienteId)
    {
        $progresos = ProgresoFisico::where('cliente_id', $clienteId)
            ->orderBy('fecha', 'asc')
            ->get();

        return response()->json($progresos);
    }

    // Guardar un nuevo registro de progreso (o actualizar si ya existe registro para esa fecha)
    public function guardarProgreso(Request $request, $clienteId)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'peso' => 'required|numeric|min:0',
            'grasa_corporal' => 'nullable|numeric|min:0|max:100',
            'circunferencia_brazo' => 'nullable|numeric|min:0',
            'circunferencia_cintura' => 'nullable|numeric|min:0',
            'imc' => 'nullable|numeric|min:0',
        ]);

        $progreso = ProgresoFisico::updateOrCreate(
            ['cliente_id' => $clienteId, 'fecha' => $data['fecha']],
            [
                'peso' => $data['peso'],
                'grasa_corporal' => $data['grasa_corporal'] ?? null,
                'circunferencia_brazo' => $data['circunferencia_brazo'] ?? null,
                'circunferencia_cintura' => $data['circunferencia_cintura'] ?? null,
                'imc' => $data['imc'] ?? null,
            ]
        );

        return response()->json([
            'message' => 'Progreso guardado correctamente',
            'progreso' => $progreso
        ]);
    }

    // Eliminar un registro de progreso específico por ID
    public function eliminarProgreso($id, $clienteId)
    {
        $progreso = ProgresoFisico::where('id', $id)
            ->where('cliente_id', $clienteId)
            ->first();

        if (!$progreso) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $progreso->delete();

        return response()->json(['message' => 'Registro eliminado correctamente']);
    }
}

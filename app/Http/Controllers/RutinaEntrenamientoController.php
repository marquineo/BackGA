<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RutinaEntrenamiento;
use App\Models\EjercicioRutina;
use App\Models\Cliente;

use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use GuzzleHttp\Client;

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

        // --- Enviar correo ---
        try {
            $this->enviarCorreoRutina($clienteId, $rutina);
        } catch (\Exception $e) {
            // Opcional: loguear error de email sin afectar respuesta
            \Log::error('Error enviando correo de rutina: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Rutina guardada correctamente',
            'rutina' => $rutina->load('ejercicios')
        ]);
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

    private function enviarCorreoRutina(int $clienteId, RutinaEntrenamiento $rutina)
    {
        $cliente = Cliente::with('usuario')->where('usuario_id', $clienteId)->firstOrFail();

        $emailCliente = $cliente->usuario->email;
        $nombreCliente = $cliente->usuario->nombre;

        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');
        $apiInstance = new TransactionalEmailsApi(new Client(), $config);

        // Preparar el contenido HTML con detalles básicos de la rutina
        $htmlContent = "<h2>Hola {$nombreCliente},Tu entrenador ha añadido una nueva rutina!</h2>";
        $htmlContent .= "<p>Tu rutina <strong>{$rutina->nombre}</strong></p>";
        if ($rutina->descripcion) {
            $htmlContent .= "<p>Descripción: {$rutina->descripcion}</p>";
        }
        $htmlContent .= "<p>Duración: {$rutina->duracion_semana} semanas</p>";

        // Opcional: tabla con ejercicios
        $htmlContent .= "<h3>Ejercicios</h3><ul>";
        foreach ($rutina->ejercicios as $ej) {
            $htmlContent .= "<li><strong>{$ej->nombre_ejercicio}</strong>: {$ej->series} series, {$ej->repeticiones} repeticiones, descanso {$ej->descanso_segundos} seg.</li>";
        }
        $htmlContent .= "</ul>";

        $email = [
            'sender' => ['name' => 'GymBroAnalytics', 'email' => 'marcoscosasclase@gmail.com'],
            'to' => [['email' => $emailCliente, 'name' => $nombreCliente]],
            'subject' => 'Nueva rutina guardada',
            'htmlContent' => $htmlContent,
        ];

        $apiInstance->sendTransacEmail($email);
    }
}

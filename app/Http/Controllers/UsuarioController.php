<?php

// app/Http/Controllers/UsuarioController.php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Cliente;
use App\Models\Entrenador;
use Illuminate\Support\Facades\DB;

use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use GuzzleHttp\Client;


class UsuarioController extends Controller
{
    public function index()
    {
        return Usuario::all();
    }
    public function indexUserByID($id)
    {
        $Usuario = Usuario::find($id);

        if (!$Usuario) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Usuario no encontrado.'
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Usuario encontrado.',
            'data' => $Usuario
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email|unique:usuarios,email',
            'contrasenya' => 'required|string|min:6',
            'rol' => 'required|in:admin,cliente,entrenador',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'contrasenya' => bcrypt($request->contrasenya),
            'rol' => $request->rol,
            'creado_en' => now()
        ]);

        return response()->json($usuario, 201);
    }
    public function showByTrainer_id($trainer_id)
    {
        $clientes = \DB::table('clientes')
            ->join('usuarios', 'clientes.usuario_id', '=', 'usuarios.id')
            ->where('clientes.entrenador_id', $trainer_id)
            ->select(
                'usuarios.id as usuario_id',
                'usuarios.nombre',
                'usuarios.email',
                'usuarios.fotoURL',
                'clientes.altura',
                'clientes.peso',
                'clientes.creado_en as cliente_creado_en'
            )
            ->get();

        return response()->json($clientes);
    }

    public function update(Request $request, Usuario $Usuario)
    {
        $Usuario->update($request->all());
        return $Usuario;
    }
    public function destroy($id)
    {
        $Usuario = Usuario::find($id);

        if (!$Usuario) {
            return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
        }

        $Usuario->delete();

        return response()->json(['mensaje' => 'Usuario eliminado correctamente'], 200);
    }

    public function checkUserLogin(Request $request)
    {
    \Log::info('Login attempt', ['input' => $request->all()]);
        $data = $request->validate([
            'Usuarioname' => 'required|string',
            'password' => 'required|string'
        ]);

        // Buscar al usuario por su nombre
        $usuario = Usuario::where('nombre', $data['Usuarioname'])->first();
        if (!$usuario || !Hash::check($data['password'], $usuario->contrasenya)) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Credenciales incorrectas.'
            ]);
        }

        /*if (!$usuario || $data['password'] !== $usuario->contrasenya) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Credenciales incorrectas.'
            ]);
        }*/
        if ($usuario->rol == "entrenador") {
            $entrenador = Entrenador::where('usuario_id', $usuario->id)->first();
            return response()->json([
                'success' => true,
                'status' => 200,
                'rol' => $usuario->rol,
                'id' => $usuario->id,
                'ishabilitado' => $entrenador->ishabilitado,
            ]);
        }
        return response()->json([
            'success' => true,
            'status' => 200,
            'rol' => $usuario->rol,
            'id' => $usuario->id,
        ]);
    }

    public function registrarEntrenador(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email|unique:usuarios,email',
            'contrasenya' => 'required|string|min:6',
            'especialidad' => 'required|string',
            'experiencia' => 'required|integer|min:0',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'contrasenya' => bcrypt($request->contrasenya),
            'rol' => 'entrenador',
            'creado_en' => now()
        ]);

        $entrenador = Entrenador::create([
            'usuario_id' => $usuario->id,
            'especialidad' => $request->especialidad,
            'experiencia' => $request->experiencia,
            'ishabilitado' => false,
            'creado_en' => now()
        ]);

        return response()->json(['usuario' => $usuario, 'entrenador' => $entrenador], 201);
    }

    public function actualizarEntrenadorPorUsuarioId(Request $request, $usuarioId)
    {
        // Validar entrada
        $request->validate([
            'nombre' => 'nullable|string',
            'email' => 'nullable|email',
            'contrasenya' => 'nullable|string|min:6',
            'especialidad' => 'nullable|string',
            'experiencia' => 'nullable|integer|min:0',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Buscar entrenador con su usuario
        $entrenador = Entrenador::with('usuario')->where('usuario_id', $usuarioId)->first();

        if (!$entrenador) {
            return response()->json(['error' => 'Entrenador no encontrado'], 404);
        }

        // Actualizar datos del usuario
        $usuario = $entrenador->usuario;

        if ($request->filled('nombre')) {
            $usuario->nombre = $request->nombre;
        }

        if ($request->filled('email')) {
            $usuario->email = $request->email;
        }


        if ($request->filled('contrasenya')) {
            $usuario->contrasenya = bcrypt($request->contrasenya);
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $path = $foto->store('entrenadores', 'public'); // <- cambia aquí
            $usuario->fotoURL = Storage::url($path);
        }


        $usuario->save();

        // Actualizar datos del entrenador
        if ($request->filled('especialidad')) {
            $entrenador->especialidad = $request->especialidad;
        }

        if ($request->filled('ishabilitado')) {
            $entrenador->ishabilitado = $request->ishabilitado;
        }

        if ($request->filled('experiencia')) {
            $entrenador->experiencia = $request->experiencia;
        }

        $entrenador->save();

        return response()->json([
            'mensaje' => 'Entrenador actualizado correctamente',
            'usuario' => $usuario,
            'entrenador' => $entrenador
        ]);
    }



    public function indexEntrenadorByID($id)
    {
        $entrenador = Entrenador::with('usuario')->where('usuario_id', $id)->first();

        if (!$entrenador) {
            return response()->json(['error' => 'Entrenador no encontrado', 'id' => $id], 404);
        }

        return response()->json([
            'id' => $entrenador->id,
            'usuario_id' => $entrenador->usuario_id,
            'nombre' => $entrenador->usuario->nombre,
            'email' => $entrenador->usuario->email,
            'fotoURL' => $entrenador->usuario->fotoURL,
            'especialidad' => $entrenador->especialidad,
            'experiencia' => $entrenador->experiencia,
            'creado_en' => $entrenador->usuario->creado_en,
            'ishabilitado' => $entrenador->ishabilitado,
        ]);
    }


    public function registrarCliente(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email', //|unique:users,email
            'contrasenya' => 'required|string',
            'altura' => 'required|numeric',
            'peso' => 'required|numeric',
            'entrenador_id' => 'required|integer',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoURL = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $path = $foto->store('entrenadores', 'public'); // <- cambia aquí
            $fotoURL = Storage::url($path);
            //\Log::info('Imagen subida:', ['url' => $fotoURL]);
        } else {
            //\Log::warning('El archivo "foto" no se recibió en el request.');
        }

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'contrasenya' => bcrypt($request->contrasenya),
            'rol' => 'cliente',
            'creado_en' => now(),
            'fotoURL' => $fotoURL
        ]);

        $cliente = Cliente::create([
            'usuario_id' => $usuario->id,
            'entrenador_id' => $request->entrenador_id,
            'altura' => $request->altura,
            'peso' => $request->peso,
            'creado_en' => now()
        ]);

        // Enviar correo electrónico
        $this->enviarCorreoNuevoCliente($usuario, $cliente);

        return response()->json(['usuario' => $usuario, 'cliente' => $cliente], 201);
    }


    public function registrarAdministrador(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'contrasenya' => Hash::make($request->password),
            'rol' => 'admin',
        ]);

        return response()->json(['usuario' => $usuario], 201);
    }

    public function getClienteByUsuarioId($usuario_id)
    {
        $cliente = Cliente::where('usuario_id', $usuario_id)->first();

        if ($cliente) {
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Cliente encontrado.',
                'data' => $cliente
            ]);
        } else {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Cliente no encontrado.'
            ]);
        }
    }

    public function actualizarAtleta(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'peso' => 'required|numeric',
            'altura' => 'required|numeric',
            'contrasenya' => 'nullable|string',
            'foto' => 'nullable|image|max:2048'
        ]);

        $usuario = Usuario::findOrFail($id);
        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;

        if ($request->filled('contrasenya')) {
            $usuario->contrasenya = bcrypt($request->contrasenya);
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoNombre = time() . '_' . $foto->getClientOriginalName();
            $path = $foto->storeAs('atletas', $fotoNombre, 'public');

            $usuario->fotoURL = Storage::url($path);

            //\Log::info('Nueva imagen almacenada:', ['url' => $usuario->fotoURL]);
        } else {
            //\Log::warning('No se recibió la imagen en el request.');
        }

        $usuario->save();

        $cliente = Cliente::where('usuario_id', $usuario->id)->firstOrFail();
        $cliente->peso = $request->peso;
        $cliente->altura = $request->altura;
        $cliente->save();

        return response()->json([
            'message' => 'Atleta actualizado correctamente',
            'data' => $usuario
        ]);
    }

    public function eliminar($id)
    {
        try {
            // Eliminar cliente asociado (si existe)
            DB::table('clientes')->where('usuario_id', $id)->delete();

            // Obtener el usuario para eliminar su foto si tiene
            $usuario = DB::table('users')->where('id', $id)->first();
            if ($usuario && $usuario->foto) {
                Storage::disk('public')->delete('uploads/' . $usuario->foto);
            }

            // Eliminar usuario
            DB::table('users')->where('id', $id)->delete();

            return response()->json(['message' => 'Atleta eliminado correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el atleta: ' . $e->getMessage()], 500);
        }
    }



    public function enviarCorreoNuevoCliente($usuario, $cliente)
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey(
            'api-key',
            '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'
        );

        $apiInstance = new TransactionalEmailsApi(new Client(), $config);

        $html = "
        <h2>Bienvenido a la familia de GymBroAnalytics!</h2>
        <p><strong>Nombre:</strong> {$usuario->nombre}</p>
        <p><strong>Email:</strong> {$usuario->email}</p>
        <p><strong>Altura:</strong> {$cliente->altura} cm</p>
        <p><strong>Peso:</strong> {$cliente->peso} kg</p>
    ";

        $email = [
            'sender' => ['name' => 'GymBroAnalytics', 'email' => 'marcoscosasclase@gmail.com'],
            'to' => [['email' => $usuario->email, 'name' => 'Admin']],
            'subject' => 'Nuevo cliente registrado: ' . $usuario->nombre,
            'htmlContent' => $html,
        ];

        try {
            $apiInstance->sendTransacEmail($email);
        } catch (\Exception $e) {
            \Log::error('Error enviando email: ' . $e->getMessage());
        }
    }

    public function getAllEntrenadores()
    {
        $entrenadores = Entrenador::with('usuario')
            ->get()
            ->map(function ($entrenador) {
                return [
                    'id' => $entrenador->id,
                    'usuario_id' => $entrenador->usuario_id,
                    'nombre' => $entrenador->usuario->nombre ?? '',
                    'especialidad' => $entrenador->especialidad,
                    'experiencia' => $entrenador->experiencia,
                    'ishabilitado' => $entrenador->ishabilitado ?? false,
                    'fotoURL' => $entrenador->usuario->fotoURL ?? ''
                ];
            });

        return response()->json($entrenadores);
    }

    public function deleteEntrenador($id)
    {
        $entrenador = Entrenador::find($id);

        if (!$entrenador) {
            return response()->json(['mensaje' => 'Entrenador no encontrado.'], 404);
        }

        $entrenador->delete();
        $usuario = $entrenador->usuario;
        $entrenador->delete();

        if ($usuario) {
            $usuario->delete();
        }


        return response()->json(['mensaje' => 'Entrenador eliminado correctamente.'], 200);
    }
}

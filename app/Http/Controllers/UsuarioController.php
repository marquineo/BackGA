<?php

// app/Http/Controllers/UsuarioController.php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Cliente;
use App\Models\Entrenador;


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
            $path = $foto->store('public/entrenadores');
            $usuario->fotoURL = config('app.url') . Storage::url($path);
        }

        $usuario->save();

        // Actualizar datos del entrenador
        if ($request->filled('especialidad')) {
            $entrenador->especialidad = $request->especialidad;
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
        ]);

    }


    public function registrarCliente(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email',//|unique:users,email
            'contrasenya' => 'required|string',
            'altura' => 'required|numeric',
            'peso' => 'required|numeric',
            'entrenador_id' => 'required|integer',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoURL = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $path = $foto->store('public/atletas');
            $fotoURL = config('app.url') . Storage::url($path);
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
}

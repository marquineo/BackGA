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
        return User::all();
    }
    public function indexUserByID($id)
    {
        $User = User::find($id);

        if (!$User) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'User no encontrado.'
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'User encontrado.',
            'data' => $User
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
        $clientes = $clientes = User::where('trainer_id', $trainer_id)
            ->where('role_id', 3) //solo clientes
            ->get();

        return response()->json($clientes);
    }
    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return $user;
    }
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['mensaje' => 'Usuario eliminado correctamente'], 200);
    }

    public function checkUserLogin(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        // Buscar al usuario por su nombre
        $usuario = Usuario::where('nombre', $data['username'])->first();

        if (!$usuario || !Hash::check($data['password'], $usuario->contrasenya)) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Credenciales incorrectas.'
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
            'creado_en' => now()
        ]);

        return response()->json(['usuario' => $usuario, 'entrenador' => $entrenador], 201);
    }


    public function registrarCliente(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email|unique:usuarios,email',
            'contrasenya' => 'required|string|min:6',
            'altura' => 'required|numeric',
            'peso' => 'required|numeric',
            'grasa_corporal' => 'required|numeric',
            'fecha_nacimiento' => 'required|date',
            'entrenador_id' => 'nullable|integer|exists:entrenadores,id'
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'contrasenya' => bcrypt($request->contrasenya),
            'rol' => 'cliente',
            'creado_en' => now()
        ]);

        $cliente = Cliente::create([
            'usuario_id' => $usuario->id,
            'entrenador_id' => $request->entrenador_id,
            'altura' => $request->altura,
            'peso' => $request->peso,
            'grasa_corporal' => $request->grasa_corporal,
            'fecha_nacimiento' => $request->fecha_nacimiento,
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

<?php

// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
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
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'peso' => 'required|numeric',
            'altura' => 'required|numeric',
            'role_id' => 'required|numeric',
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
        $user = User::create([
            'name' => $request->nombre,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'peso' => $request->peso,
            'altura' => $request->altura,
            'role_id' => $request->role_id,
            'trainer_id' => $request->trainer_id,
            'fotoURL' => $fotoURL
        ]);
        return response()->json($user, 201);
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
        // Validar datos de la solicitud
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        // Buscar al usuario por su nombre de usuario
        $user = User::where('user', $data['username'])->first();

        // Verificar las credenciales
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Credenciales incorrectas.'
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 200,
            'rol' => $user->role_id,
            'id' => $user->id,
        ]);
    }
}

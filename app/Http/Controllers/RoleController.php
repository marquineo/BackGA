<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Método para crear un nuevo rol
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name', // Validamos que el nombre sea único
        ]);

        // Crear el nuevo rol
        $role = Role::create([
            'name' => $request->name,
        ]);

        // Retornar respuesta de éxito con el rol creado
        return response()->json(['role' => $role], 201);
    }

    // Método para obtener todos los roles
    public function index()
    {
        $roles = Role::all(); // Obtener todos los roles
        return response()->json($roles);
    }

    // Método para obtener un rol por su ID
    public function show($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['error' => 'Rol no encontrado'], 404);
        }

        return response()->json($role);
    }

    // Método para actualizar un rol
    public function update(Request $request, $id)
    {
        // Validar los datos recibidos
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id, // Validamos que el nombre sea único, excepto el rol actual
        ]);

        // Buscar el rol
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['error' => 'Rol no encontrado'], 404);
        }

        // Actualizar el nombre del rol
        $role->name = $request->name;
        $role->save();

        // Retornar la respuesta con el rol actualizado
        return response()->json(['role' => $role], 200);
    }

    // Método para eliminar un rol
    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['error' => 'Rol no encontrado'], 404);
        }

        $role->delete();

        return response()->json(['message' => 'Rol eliminado exitosamente'], 200);
    }
}

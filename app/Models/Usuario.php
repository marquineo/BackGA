<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';  // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id';  // Clave primaria
    public $timestamps = false;  // No se usan los timestamps automáticos (created_at y updated_at)
    
    protected $fillable = [
        'nombre',
        'email',
        'contrasenya',
        'rol',
        'creado_en',
    ];
    
    // Validación o personalización de métodos pueden ir aquí más tarde
    public function cliente()
{
    return $this->hasOne(Cliente::class);
}

public function entrenador()
{
    return $this->hasOne(Entrenador::class);
}

}

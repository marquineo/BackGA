<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrenador extends Model
{
    use HasFactory;

    // Especifica el nombre de la tabla (si no sigue la convención plural en inglés)
    protected $table = 'entrenadores';

        public $timestamps = false;

    // Definir qué campos son asignables en masa
    protected $fillable = [
        'usuario_id',
        'especialidad',
        'experiencia',
    ];

    // Definir la relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Un entrenador tiene muchos clientes
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
}

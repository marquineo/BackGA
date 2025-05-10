<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroProgreso extends Model
{
    use HasFactory;

    // Especifica el nombre de la tabla (si no sigue la convención plural en inglés)
    protected $table = 'registros_progreso';

    // Definir qué campos son asignables en masa
    protected $fillable = [
        'cliente_id', 
        'fecha', 
        'peso', 
        'grasa_corporal', 
        'repeticiones', 
        'tiempo_entrenamiento',
    ];

    // Definir la relación con el modelo Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}

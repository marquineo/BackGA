<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RutinaEntrenamiento extends Model
{
    use HasFactory;

    protected $table = 'rutinas_entrenamiento';

    protected $fillable = [
        'cliente_id',
        'entrenador_id',
        'nombre',
        'descripcion',
        'duracion_semana'
    ];

    public function ejercicios()
    {
        return $this->hasMany(EjercicioRutina::class, 'rutina_id');
    }
}

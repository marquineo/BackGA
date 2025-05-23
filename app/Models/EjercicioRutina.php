<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EjercicioRutina extends Model
{
    use HasFactory;

    protected $table = 'ejercicios_rutina';

        public $timestamps = false;

    protected $fillable = [
        'rutina_id',
        'nombre_ejercicio',
        'repeticiones',
        'series',
        'dia_semana',
        'descanso_segundos',
        'orden',
        'rpe'
    ];

    public function rutina()
    {
        return $this->belongsTo(RutinaEntrenamiento::class, 'rutina_id');
    }
}

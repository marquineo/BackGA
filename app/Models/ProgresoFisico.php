<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgresoFisico extends Model
{
    use HasFactory;

    protected $table = 'progresos_fisicos';

    public $timestamps = false;

    protected $fillable = [
        'cliente_id',
        'fecha',
        'peso',
        'grasa_corporal',
        'circunferencia_brazo',
        'circunferencia_cintura',
        'imc'
    ];
}

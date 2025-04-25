<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'usuario_id',
        'altura',
        'peso',
        'grasa_corporal',
        'fecha_nacimiento',
    ];

    // Relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}

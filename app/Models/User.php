<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = ['username', 'name', 'email', 'password', 'peso', 'altura', 'role_id', 'trainer_id','fotoURL'];
    protected $hidden = ['password'];
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function clients()
    {
        return $this->hasMany(User::class, 'trainer_id');
    }
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
    // Mutator para encriptar la contraseña automáticamente
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password); // bcrypt la contraseña antes de guardarla
    }
}

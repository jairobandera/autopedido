<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Si tu tabla tiene un nombre diferente
    protected $table = 'usuarios';  // Asegúrate de que el nombre de la tabla sea correcto

    // Si tus columnas son diferentes
    protected $fillable = [
        'nombre',    // El nombre del campo en tu tabla
        'contrasena',
        'rol',       // El campo que usas para los roles
        'activo',
    ];

    protected $hidden = [
        'contrasena', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Si usas un campo diferente para las contraseñas o cualquier otro ajuste específico
    public function setPasswordAttribute($value)
    {
        $this->attributes['contrasena'] = bcrypt($value);
    }
}

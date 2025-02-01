<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'tipo_inscripcion', 'es_estudiante', 'es_admin'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'es_estudiante' => 'boolean',
        'es_admin' => 'boolean',
    ];

    public function inscripciones()
    {
        return $this->hasMany(Inscription::class);
    }

    public function pagos()
    {
        return $this->hasMany(Payment::class);
    }
}

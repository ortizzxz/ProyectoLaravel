<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = ['user_id', 'evento_id', 'tipo_inscripcion', 'monto_pagado', 'estado'];


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

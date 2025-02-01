<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'foto', 'areas_experiencia', 'redes_sociales'];

    protected $casts = [
        'areas_experiencia' => 'array',
    ];

    public function eventos()
    {
        return $this->hasMany(Event::class);
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'tipo', 'fecha', 'hora_inicio', 'hora_fin', 'ponente_id'];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'time',
        'hora_fin' => 'time',
    ];

    public function ponente()
    {
        return $this->belongsTo(Speaker::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscription::class);
    }
}

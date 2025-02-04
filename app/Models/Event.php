<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;
    protected $table = 'eventos'; // Indicar que este modelo usa la tabla "ponentes"

    protected $fillable = [
        'titulo',
        'tipo',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'ponente_id',
        'cupo_maximo'
    ];
    
    protected $casts = [
        'fecha' => 'date',  
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
    ];

    public function ponente()
    {
        return $this->belongsTo(Speaker::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscription::class, 'evento_id');
    }
    


    // Validar la superposición de horarios
    public static function validarSuperposicionConDescanso($fecha, $hora_inicio, $hora_fin, $tipo)
    {
        // Definir los descansos de media mañana y media tarde
        $descanso_manana_inicio = Carbon::createFromFormat('H:i', '12:00');
        $descanso_manana_fin = Carbon::createFromFormat('H:i', '12:30');
        $descanso_tarde_inicio = Carbon::createFromFormat('H:i', '16:00');
        $descanso_tarde_fin = Carbon::createFromFormat('H:i', '16:30');

        $hora_inicio = Carbon::createFromFormat('H:i', $hora_inicio);
        
        $hora_fin = Carbon::parse($hora_fin)->format('H:i');
        $hora_fin = Carbon::createFromFormat('H:i', $hora_fin);
        
        if (
            ($hora_inicio < $descanso_manana_fin && $hora_fin > $descanso_manana_inicio) ||
            ($hora_inicio < $descanso_tarde_fin && $hora_fin > $descanso_tarde_inicio)
        ) {
            return 'Esa es la hora del descanso'; 
        }

        $eventoExistente = Event::where('fecha', $fecha)
            ->where(function ($query) use ($hora_inicio, $hora_fin) {
                $query->whereBetween('hora_inicio', [$hora_inicio, $hora_fin])
                    ->orWhereBetween('hora_fin', [$hora_inicio, $hora_fin])
                    ->orWhere(function ($query) use ($hora_inicio, $hora_fin) {
                        $query->where('hora_inicio', '<', $hora_inicio)
                            ->where('hora_fin', '>', $hora_fin);
                    });
            })
            ->where('tipo', $tipo)
            ->exists();

        if ($eventoExistente) {
            return 'Ya existe un evento en este horario';
        }

        return null; // no hay conflicto
    }




}

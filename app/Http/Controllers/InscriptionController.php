<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;


class InscriptionController extends Controller
{
    // Inscribir al usuario en un evento
    public function inscribir(Request $request)
    {
        $user = Auth::user();
        $evento_id = $request->evento_id;
        $tipo_inscripcion = $request->tipo_inscripcion;

        $evento = Event::findOrFail($evento_id);

        // Validar que el usuario no supere los límites
        $conferencias_inscritas = Inscription::where('user_id', $user->id)->whereHas('evento', function ($query) {
            $query->where('tipo', 'conferencia');
        })->count();

        $talleres_inscritos = Inscription::where('user_id', $user->id)->whereHas('evento', function ($query) {
            $query->where('tipo', 'taller');
        })->count();

        if ($evento->tipo === 'conferencia' && $conferencias_inscritas >= 5) {
            return back()->with('error', 'Ya has alcanzado el límite de 5 conferencias.');
        }

        if ($evento->tipo === 'taller' && $talleres_inscritos >= 4) {
            return back()->with('error', 'Ya has alcanzado el límite de 4 talleres.');
        }

        // Validar si hay cupo disponible
        $inscritos_actuales = Inscription::where('evento_id', $evento_id)->count();
        if ($inscritos_actuales >= $evento->cupo_maximo) {
            return back()->with('error', 'No hay más cupos disponibles para este evento.');
        }

        // Si la inscripción es gratuita, validar que es estudiante
        if ($tipo_inscripcion === 'gratuita' && !$user->es_estudiante) {
            return back()->with('error', 'Debes ser estudiante del centro para acceder gratuitamente.');
        }

        // Registrar la inscripción
        try {
            Inscription::create([
                'user_id' => $user->id,
                'evento_id' => $evento_id,
                'tipo_inscripcion' => $tipo_inscripcion,
            ]);
            return back()->with('success', 'Inscripción realizada con éxito.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->with('error', 'Usted se encuentra ya registrado para este evento.');
            }
        }
        

    }
}

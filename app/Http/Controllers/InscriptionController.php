<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription;
use App\Models\Event;

class InscriptionController extends Controller
{
    // Inscribir al usuario en un evento
    public function inscribir(Request $request)
    {
        $evento = Event::findOrFail($request->evento_id);
        
        // Verificar si hay espacio disponible en el evento
        if ($evento->inscripciones()->count() < $evento->cupos) {
            Inscription::create([
                'user_id' => auth()->user()->id,
                'evento_id' => $evento->id,
            ]);
            
            return redirect()->route('eventos.index')->with('success', 'Inscripción realizada con éxito.');
        }

        return back()->withErrors(['error' => 'No hay espacio disponible en este evento.']);
    }
}

<?php
// app/Http/Controllers/EventoController.php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Speaker;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $eventos = Event::with('ponente')->get();
        dd($eventos); // This will dump and die, showing you the contents of $eventos
        return view('eventos.index', compact('eventos'));
    }
    

    public function create()
    {
        // Obtener todos los ponentes disponibles
        $ponentes = Speaker::all();
        return view('eventos.create', compact('ponentes'));
    }

    public function store(Request $request)
    {
        // Validar la información del evento
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'ponente_id' => 'required|exists:ponentes,id',
        ]);

        // Crear un nuevo evento
        Event::create($request->all());

        return redirect()->route('eventos.index')->with('success', 'Evento registrado con éxito.');
    }

    public function destroy($id)
    {
        // Eliminar el evento por ID
        Event::findOrFail($id)->delete();
        return redirect()->route('eventos.index')->with('success', 'Evento eliminado con éxito.');
    }
}

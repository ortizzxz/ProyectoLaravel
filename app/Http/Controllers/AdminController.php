<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard'); // Aquí se llama a la vista admin.dashboard
    }

    public function listEventos()
    {
        // Obtener todos los eventos, puedes agregar filtros o relaciones según lo necesites
        $eventos = Event::all();
        return view('admin.eventos.index', compact('eventos'));
    }
    
    
    // Listado de ponentes
    public function listPonentes()
    {
        $ponentes = Speaker::paginate(10); // 10 por página
        return view('admin.ponentes.index', compact('ponentes'));
    }

    // Mostrar formulario de creación de eventos
    public function createEvento()
    {
        // Obtener ponentes disponibles para asociar un evento
        $ponentes = Speaker::all();
        return view('admin.eventos.create', compact('ponentes'));
    }

    // Almacenar un nuevo evento
    public function storeEvento(Request $request)
    {
        // Validar que no haya superposiciones de eventos
        $hora_inicio = $request->hora_inicio;
        // Convertir la hora de inicio a un objeto Carbon (para trabajar con fechas y horas)
        $calcularHora = Carbon::createFromFormat('H:i', $hora_inicio);

        // Calcular hora de fin sumando 55 minutos
        $hora_fin = $calcularHora->copy()->addMinutes(55);

        $fecha = $request->fecha;
        $tipo = $request->tipo; // conferencia o taller
        $ponente_id = $request->ponente_id;

        // Comprobar si el ponente está en otro evento que se solape
        $eventoSuperpuestoPonente = Event::where('fecha', $fecha)
            ->where('ponente_id', $ponente_id) // Buscar eventos del mismo ponente
            ->where(function ($query) use ($hora_inicio, $hora_fin) {
                $query->whereBetween('hora_inicio', [$hora_inicio, $hora_fin])
                    ->orWhereBetween('hora_fin', [$hora_inicio, $hora_fin])
                    ->orWhere(function ($query) use ($hora_inicio, $hora_fin) {
                        $query->where('hora_inicio', '<', $hora_inicio)
                            ->where('hora_fin', '>', $hora_fin);
                    });
            })
            ->exists();

        if ($eventoSuperpuestoPonente) {
            return redirect()->route('admin.eventos')->with('error', 'El ponente ya está asignado a otro evento en este horario.');
        }

        // Comprobar si hay superposiciones con descansos
        if ($mensaje = Event::validarSuperposicionConDescanso($fecha, $hora_inicio, $hora_fin, $tipo)) {
            return redirect()->route('admin.eventos')->with('error', $mensaje);
        }

        // Crear el evento si no hay superposición
        Event::create([
            'titulo' => $request->titulo,
            'tipo' => $request->tipo,
            'fecha' => $request->fecha,
            'hora_inicio' => $hora_inicio,
            'hora_fin' => $hora_fin,
            'ponente_id' => $request->ponente_id,
        ]);

        return redirect()->route('admin.eventos')->with('success', 'Evento creado exitosamente.');
    }

    // Eliminar un evento
    public function destroyEvento($id)
    {
        $evento = Event::findOrFail($id);
        $evento->delete();

        return redirect()->route('admin.eventos')->with('success', 'Evento eliminado con éxito.');
    }
}

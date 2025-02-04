<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Payment;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;



class AdminController extends Controller
{

    // Usarios
    public function listUsuarios()
    {
        // Obtener todos los usuarios de la base de datos
        $usuarios = User::all();

        // Retornar la vista con los datos
        return view('admin.usuarios', compact('usuarios'));
    }

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

    // Guardar un ponente
    public function storePonente(Request $request)
    {
        // Validar datos
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Solo imágenes, máximo 2MB
            'areas_experiencia' => 'nullable|array', // Asegurar que es un array
            'areas_experiencia.*' => 'string|max:255', // Cada área debe ser un string
            'redes_sociales' => 'nullable|string|max:500|url', // Asegurar que es una URL válida
        ]);

        // Manejar la imagen si se sube
        if ($request->hasFile('foto')) {
            $validatedData['foto'] = $request->file('foto')->store('ponentes', 'public');
        }

        // Guardar en la base de datos
        Speaker::create($validatedData);

        return redirect()->route('admin.ponentes')->with('success', 'Ponente registrado correctamente.');
    }

    // Borrar un ponente

    public function destroyPonente($id)
    {
        // Buscar el ponente en la base de datos
        $ponente = Speaker::find($id);

        // Verificar si el ponente existe
        if (!$ponente) {
            return redirect()->route('admin.ponentes')->with('error', 'Ponente no encontrado.');
        }

        // Eliminar la imagen asociada (si existe)
        if ($ponente->foto) {
            \Storage::disk('public')->delete($ponente->foto);
        }

        // Eliminar el ponente
        $ponente->delete();

        return redirect()->route('admin.ponentes')->with('success', 'Ponente eliminado correctamente.');
    }


    // Mostrar formulario de creación de eventos
    public function createEvento()
    {
        // Obtener ponentes disponibles para asociar un evento
        $ponentes = Speaker::all();
        return view('admin.eventos.create', compact('ponentes'));
    }

    public function storeEvento(Request $request)
    {
        // Reglas de validación
        $rules = [
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|in:conferencia,taller',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'ponente_id' => 'required|exists:ponentes,id',
        ];

        // Mensajes personalizados
        $messages = [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.string' => 'El título debe ser una cadena de texto.',
            'tipo.in' => 'El tipo debe ser conferencia o taller.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.after_or_equal' => 'No se pueden crear eventos en fechas pasadas.',
            'hora_inicio.date_format' => 'Formato de hora inválido.',
            'ponente_id.exists' => 'El ponente seleccionado no es válido.',
        ];

        // Validar los datos
        $validatedData = $request->validate($rules, $messages);

        // Sanitización
        $titulo = e(Str::of($request->titulo)->trim());
        $tipo = $request->tipo;
        $fecha = Carbon::parse($request->fecha)->format('Y-m-d');
        $hora_inicio = $request->hora_inicio;
        $ponente_id = $request->ponente_id;

        // Convertir la hora de inicio a objeto Carbon
        $calcularHora = Carbon::createFromFormat('H:i', $hora_inicio);

        // Calcular la hora de fin (sumando 55 minutos)
        $hora_fin = $calcularHora->copy()->addMinutes(55)->format('H:i');

        // Validar que el ponente no tenga otro evento en este horario
        $eventoSuperpuestoPonente = Event::where('fecha', $fecha)
            ->where('ponente_id', $ponente_id)
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
            return redirect()->route('admin.eventos')->with('error', 'El ponente ya tiene otro evento en este horario.');
        }

        //  Validar que no haya otra conferencia o taller a la misma hora
        $eventoSuperpuestoTipo = Event::where('fecha', $fecha)
            ->where('tipo', $tipo) // Se asegura de que sea otro evento del mismo tipo
            ->where(function ($query) use ($hora_inicio, $hora_fin) {
                $query->whereBetween('hora_inicio', [$hora_inicio, $hora_fin])
                    ->orWhereBetween('hora_fin', [$hora_inicio, $hora_fin])
                    ->orWhere(function ($query) use ($hora_inicio, $hora_fin) {
                        $query->where('hora_inicio', '<', $hora_inicio)
                            ->where('hora_fin', '>', $hora_fin);
                    });
            })
            ->exists();

        if ($eventoSuperpuestoTipo) {
            return redirect()->route('admin.eventos')->with('error', 'Ya existe otra ' . $tipo . ' en este horario.');
        }

        //  Validar superposición con descansos (si es necesario)
        if ($mensaje = Event::validarSuperposicionConDescanso($fecha, $hora_inicio, $hora_fin, $tipo)) {
            return redirect()->route('admin.eventos')->with('error', $mensaje);
        }

        //  Crear evento
        Event::create([
            'titulo' => $titulo,
            'tipo' => $tipo,
            'fecha' => $fecha,
            'hora_inicio' => $hora_inicio,
            'hora_fin' => $hora_fin,
            'ponente_id' => $ponente_id,
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

    // Mostrar tesorería 

    // ATENCION CORREGIR // 
    public function showIngresos()
    {
        // Obtener los ingresos con el nombre del usuario
        $ingresos = DB::table('pagos')
            ->join('users', 'pagos.user_id', '=', 'users.id')
            ->select('pagos.id', 'pagos.estado', 'pagos.monto', 'pagos.created_at', 'users.name as user_name')
            ->get();

        return view('admin.ingresos', compact('ingresos'));
    }

}

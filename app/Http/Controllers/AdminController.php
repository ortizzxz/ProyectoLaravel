<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\StorePonenteRequest;
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
        $eventos = Event::paginate(6);
        return view('admin.eventos.index', compact('eventos'));
    }



    // Listado de ponentes
    public function listPonentes()
    {
        $ponentes = Speaker::paginate(10); // 10 por página
        return view('admin.ponentes.index', compact('ponentes'));
    }

    // Guardar un ponente
    public function storePonente(StorePonenteRequest $request)
    {
        $validatedData = $request->validated();

        // Manejar la imagen 
        if ($request->hasFile('foto')) {
            $validatedData['foto'] = $request->file('foto')->store('ponentes', 'public');
        }

        // Guardar ponente
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

    public function storeEvento(StoreEventoRequest $request)
    {
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
    public function showIngresos()
    {
        // Obtener los ingresos con el nombre del usuario, paginados
        $ingresos = DB::table('pagos')
            ->join('users', 'pagos.user_id', '=', 'users.id')
            ->select('pagos.id', 'pagos.estado', 'pagos.monto', 'pagos.created_at', 'users.name as user_name')
            ->paginate(8); // Paginar 15 resultados por página

        return view('admin.ingresos', compact('ingresos'));
    }


}

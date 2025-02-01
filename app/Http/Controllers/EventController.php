<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Event;
    use App\Models\Speaker;

    class EventController extends Controller
    {
        // Mostrar todos los eventos disponibles
        public function index()
        {
            $eventos = Event::all(); // O con filtros según el tipo (conferencia/taller)
            return view('eventos.index', compact('eventos'));
        }
    }

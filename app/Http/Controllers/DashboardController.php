<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch all events (conferencias & talleres)
        $eventos = Event::orderBy('fecha', 'asc')->get();
        return view('dashboard', compact('eventos'));
    }
}

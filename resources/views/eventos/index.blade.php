<!-- resources/views/eventos.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Eventos Disponibles</h1>
    
    <ul>
        @foreach ($eventos as $evento)
            <li>
                <h3>{{ $evento->titulo }}</h3>
                <p>{{ $evento->descripcion }}</p>
                <form action="{{ url('/inscribirse') }}" method="POST">
                    @csrf
                    <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                    <button type="submit">Inscribirme</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection

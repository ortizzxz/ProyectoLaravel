@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-4 text-center text-blue-600">Listado de Eventos</h1>

        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-200 text-red-800 p-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <table class="w-full border-collapse border border-gray-300 shadow-lg rounded-lg">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Título</th>
                    <th class="border p-2">Tipo</th>
                    <th class="border p-2">Fecha</th>
                    <th class="border p-2">Hora de Inicio</th>
                    <th class="border p-2">Hora de Fin</th>
                    <th class="border p-2">Ponente</th>
                    <th class="border p-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($eventos as $evento)
                    <tr class="hover:bg-gray-100">
                        <td class="border p-2">{{ $evento->titulo }}</td>
                        <td class="border p-2 capitalize">{{ $evento->tipo }}</td>
                        <td class="border p-2">{{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}</td>
                        <td class="border p-2">{{ $evento->hora_inicio }}</td>
                        <td class="border p-2">{{ $evento->hora_fin }}</td>
                        <td class="border p-2">
                            @if($evento->ponente)
                                {{ $evento->ponente->nombre }}
                            @else
                                Sin ponente asignado
                            @endif
                        </td>
                        <td class="border p-2">
                            <form action="{{ route('admin.eventos.destroy', $evento->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600 focus:outline-none">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 text-center">
            <a href="{{ route('admin.eventos.create') }}" class="px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none">
                Crear Evento
            </a>
        </div>
    </div>
@endsection

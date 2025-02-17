@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-10 max-w-4xl p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-bold mb-6 text-center text-blue-600">Editar Evento</h1>

        <form action="{{ route('admin.eventos.update', $evento->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Título -->
            <div class="mb-4">
                <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
                <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $evento->titulo) }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required maxlength="255">
                @error('titulo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipo de evento -->
            <div class="mb-4">
                <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo de Evento</label>
                <select name="tipo" id="tipo"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required>
                    <option value="conferencia" {{ $evento->tipo == 'conferencia' ? 'selected' : '' }}>Conferencia</option>
                    <option value="taller" {{ $evento->tipo == 'taller' ? 'selected' : '' }}>Taller</option>
                </select>
                @error('tipo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha -->
            <div class="mb-4">
                <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                <input type="date" name="fecha" id="fecha" value="{{ old('fecha', $evento->fecha->format('Y-m-d')) }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required min="2025-02-20" max="2025-02-21">
                @error('fecha')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hora de inicio -->
            <div class="mb-4">
                <label for="hora_inicio" class="block text-sm font-medium text-gray-700">Hora de Inicio</label>
                <input type="time" name="hora_inicio" id="hora_inicio" value="{{ old('hora_inicio', $evento->hora_inicio->format('H:i')) }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required>
                @error('hora_inicio')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ponente -->
            <div class="mb-4">
                <label for="ponente_id" class="block text-sm font-medium text-gray-700">Ponente</label>
                <select name="ponente_id" id="ponente_id"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required>
                    @foreach ($ponentes as $ponente)
                        <option value="{{ $ponente->id }}" {{ $evento->ponente_id == $ponente->id ? 'selected' : '' }}>
                            {{ $ponente->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('ponente_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botón de submit -->
            <div class="flex justify-center mt-6">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Actualizar Evento
                </button>
            </div>
        </form>
    </div>
@endsection

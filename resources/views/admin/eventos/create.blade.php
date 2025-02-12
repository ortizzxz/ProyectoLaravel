@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-10 max-w-4xl p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-bold mb-6 text-center text-blue-600">Crear Evento</h1>

        <form action="{{ route('admin.eventos.store') }}" method="POST">
            @csrf

            <!-- Título -->
            <div class="mb-4">
                <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
                <input type="text" name="titulo" id="titulo"
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
                    <option value="conferencia">Conferencia</option>
                    <option value="taller">Taller</option>
                </select>
                @error('tipo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fecha -->
            <div class="mb-4">
                <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                <input type="date" name="fecha" id="fecha"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required min="2025-02-20" max="2025-02-21">
                @error('fecha')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- JS Fecha -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var fechaInput = document.getElementById('fecha');

                    fechaInput.addEventListener('input', function () {
                        var selectedDate = new Date(this.value);
                        var day = selectedDate.getUTCDay();

                        // 4 is Jueves, 5 is Viernes
                        if (day !== 4 && day !== 5) {
                            this.value = '';
                            alert('Por favor, seleccione solo jueves 20 o viernes 21 de febrero.');
                        }
                    });
                });
            </script>

            <!-- Hora de inicio -->
            <div class="mb-4">
                <label for="hora_inicio" class="block text-sm font-medium text-gray-700">Hora de Inicio</label>
                <input type="time" name="hora_inicio" id="hora_inicio"
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
                        <option value="{{ $ponente->id }}">{{ $ponente->nombre }}</option>
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
                    Registrar Evento
                </button>
            </div>
        </form>

    </div>
@endsection
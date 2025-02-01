@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-10 px-6">

        <h1 class="text-3xl text-center font-bold text-blue-600 mb-6">Gestión de Ponentes</h1>

        <!-- Mensajes de éxito -->
        @if(session('success'))
            <div class="text-center bg-green-200 text-green-800 p-3 rounded-lg mb-6 shadow-md">
                {{ session('success') }}
            </div>
        @endif

        <!-- Mensajes de error -->
        @if ($errors->any())
            <div class="text-center bg-red-200 text-red-800 p-3 rounded-lg mb-6 shadow-md">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Botón para desplegar el formulario -->
        <button id="toggleForm" class="block mx-auto px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md mb-6 transition-transform transform hover:scale-105">
            Registrar Ponente
        </button>

        <!-- Formulario de Registro de Ponentes -->
        <div id="formContainer" class="bg-white p-8 shadow-lg rounded-lg mb-6 hidden transition-all duration-300 ease-in-out">
            <h2 class="text-2xl font-semibold text-blue-600 mb-4">Registrar Ponente</h2>
            <form action="{{ route('admin.ponentes') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700">Nombre:</label>
                    <input type="text" name="nombre" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700">Descripción:</label>
                    <textarea name="descripcion" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700">Foto:</label>
                    <input type="file" name="foto" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700">Áreas de Experiencia:</label>
                    <input type="text" name="areas_experiencia[]" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700">Redes Sociales:</label>
                    <input type="text" name="redes_sociales" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 mt-4">
                    Registrar
                </button>
            </form>
        </div>

        <!-- Listado de Ponentes -->
        <div class="bg-white p-8 shadow-lg rounded-lg">
            <h2 class="text-2xl font-semibold text-blue-600 mb-4">Lista de Ponentes</h2>
            <table class="w-full border-collapse text-left table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-4 border-b text-sm font-medium text-gray-700">Nombre</th>
                        <th class="p-4 border-b text-sm font-medium text-gray-700">Descripción</th>
                        <th class="p-4 border-b text-sm font-medium text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ponentes as $ponente)
                        <tr class="hover:bg-gray-100 transition-colors">
                            <td class="p-4 border-b text-sm text-gray-800">{{ $ponente->nombre }}</td>
                            <td class="p-4 border-b text-sm text-gray-800">{{ $ponente->descripcion }}</td>
                            <td class="p-4 border-b text-sm">
                                <form action="{{ url('/admin/ponentes/'.$ponente->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 focus:outline-none">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $ponentes->links() }}
            </div>
        </div>
    </div>

    <!-- JavaScript para manejar la visibilidad del formulario -->
    <script>
        document.getElementById('toggleForm').addEventListener('click', function() {
            const formContainer = document.getElementById('formContainer');
            formContainer.classList.toggle('hidden');
        });
    </script>
@endsection

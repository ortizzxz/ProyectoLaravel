@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10 px-6">
    <h1 class="text-3xl text-center font-bold text-blue-600 mb-6">Editar Ponente</h1>

    <div class="bg-white p-8 shadow-lg rounded-lg mb-6">
        <form action="{{ route('admin.ponentes.update', $ponente->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700">Nombre:</label>
                <input type="text" name="nombre" value="{{ old('nombre', $ponente->nombre) }}" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700">Descripción:</label>
                <textarea name="descripcion" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $ponente->descripcion) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700">Foto:</label>
                <input type="file" name="foto" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @if($ponente->foto)
                    <img src="{{ asset('storage/' . $ponente->foto) }}" alt="Foto actual" class="mt-2 w-32 h-32 object-cover">
                @endif
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700">Áreas de Experiencia:</label>
                <input type="text" name="areas_experiencia[]" value="{{ old('areas_experiencia', implode(', ', $ponente->areas_experiencia ?? [])) }}" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700">Redes Sociales:</label>
                <input type="text" name="redes_sociales" value="{{ old('redes_sociales', $ponente->redes_sociales) }}" class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 mt-4">
                Actualizar Ponente
            </button>
        </form>
    </div>
</div>
@endsection

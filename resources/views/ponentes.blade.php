@extends('layouts.app') 
@section('content') 

<div class="container mx-auto my-8">
    <h1 class="text-3xl font-bold text-center mb-8">Lista de Ponentes</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @foreach($ponentes as $ponente)

            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <img src="{{ asset('storage/' . $ponente->foto) }}" alt="Foto de {{ $ponente->nombre }}" 
                    class="w-full h-74object-cover rounded-t-lg mb-4">
                <div class="speaker-info">
                    <h3 class="text-xl font-semibold text-gray-800">{{ $ponente->nombre }}</h3>
                    <p class="text-gray-600 mt-2"><strong>Descripción:</strong> {{ $ponente->descripcion }}</p>
                    <p class="text-gray-600 mt-2"><strong>Áreas de experiencia:</strong>
                        <ul class="list-disc ml-6">
                            @foreach(is_array($ponente->areas_experiencia) ? $ponente->areas_experiencia : json_decode($ponente->areas_experiencia) as $area)
                                <li class="text-gray-600">{{ $area }}</li>
                            @endforeach
                        </ul>
                    </p>
                    <p class="mt-4 text-blue-600"><strong>Redes Sociales:</strong> 
                        <a href="{{ $ponente->redes_sociales }}" target="_blank" class="hover:underline">LinkedIn</a>
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Pagination Links -->
    <div class="mt-8">
        {{ $ponentes->links() }}
    </div>
</div>

@endsection

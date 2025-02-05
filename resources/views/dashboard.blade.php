<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensajes de éxito y error -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($eventos as $evento)

                            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                <div class="px-4 py-5 sm:p-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        {{ $evento->titulo }}
                                    </h3>
                                    <div class="mt-2 max-w-xl text-sm text-gray-500">
                                        <p><strong>Tipo:</strong> {{ ucfirst($evento->tipo) }}</p>
                                        <p><strong>Fecha:</strong>
                                            {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}</p>
                                        <p><strong>Hora:</strong>
                                            {{ \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i')  }} -
                                            {{ \Carbon\Carbon::parse($evento->hora_fin)->format('H:i')  }}</p>
                                        <p><strong>Cupo Disponible:</strong>
                                            {{ $evento->cupo_maximo - $evento->inscripciones->count() }}</p>
                                    </div>
                                    @if ($evento->cupo_maximo > $evento->inscripciones->count())
                                        <form action="{{ route('pay.with.paypal') }}" method="POST" class="mt-5">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                            <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                                            <div class="mb-3">
                                                <label for="tipo_inscripcion"
                                                    class="block text-sm font-medium text-gray-700">Tipo de inscripción:</label>
                                                <select name="tipo_inscripcion"
                                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                    <option value="presencial">Presencial</option>
                                                    <option value="virtual">Virtual</option>
                                                    @if (auth()->user()->es_estudiante)
                                                        <option value="gratuita">Gratuita (estudiante)</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <input type="hidden" name="total" value="10.00"> <!-- Set the appropriate price -->
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Pagar
                                            </button>
                                        </form>

                                    @else
                                        <p class="mt-3 text-sm text-red-600">Cupo completo</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
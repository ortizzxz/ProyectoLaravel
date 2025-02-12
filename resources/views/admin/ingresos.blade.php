@extends('layouts.app') <!-- Extends the main layout -->

@section('content')
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-semibold text-center text-blue-600">Ingresos</h1>
        
        <!-- Table to display the income data -->
        <div class="mt-6 bg-white p-6 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Detalles de Ingresos</h2>
            
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-blue-100">
                        <th class="py-2 px-4 text-left border-b">ID</th>
                        <th class="py-2 px-4 text-left border-b">Usuario</th> 
                        <th class="py-2 px-4 text-left border-b">Estado</th>
                        <th class="py-2 px-4 text-left border-b">Monto</th>
                        <th class="py-2 px-4 text-left border-b">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ingresos as $ingreso)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $ingreso->id }}</td>
                            <td class="py-2 px-4 border-b">{{ $ingreso->user_name }}</td> <!-- Mostrar el nombre del usuario -->
                            <td class="py-2 px-4 border-b">{{ $ingreso->estado }}</td>
                            <td class="py-2 px-4 border-b">{{ $ingreso->monto }} €</td>
                            <td class="py-2 px-4 border-b">{{ $ingreso->created_at }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
    {{ $ingresos->links() }}
@endsection

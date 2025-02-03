@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-semibold text-center text-blue-600">Lista de Usuarios</h1>
        
        <div class="mt-6 bg-white p-6 shadow-lg rounded-lg">
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-blue-100">
                        <th class="py-2 px-4 text-left border-b">ID</th>
                        <th class="py-2 px-4 text-left border-b">Nombre</th>
                        <th class="py-2 px-4 text-left border-b">Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $usuario->id }}</td>
                            <td class="py-2 px-4 border-b">{{ $usuario->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $usuario->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

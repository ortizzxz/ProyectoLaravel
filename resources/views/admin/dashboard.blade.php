@extends('layouts.app') 

@section('content') 
<div class="container mx-auto mt-10 max-w-6xl p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-3xl font-bold text-center text-blue-600 mb-8">Admin Dashboard</h1>
    <p class="text-center text-gray-700 mb-6">Bienvenido al panel de administración. Selecciona una sección:</p>

    <!-- Grid de Tarjetas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Tarjeta Ponentes -->
        <a href="{{ route('admin.ponentes') }}" class="block bg-white shadow-md rounded-lg p-6 text-center hover:bg-blue-50 transition transform hover:scale-105">
            <div class="flex justify-center mb-4">
                <svg class="w-16 h-16 text-black-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 10-8 0 4 4 0 008 0zM12 14c-4.418 0-8 1.79-8 4v1h16v-1c0-2.21-3.582-4-8-4z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Ponentes</h2>
            <p class="text-gray-600 text-sm mt-2">Gestiona los ponentes del evento.</p>
        </a>
        
        <!-- Tarjeta Asistentes -->
        <a href="{{ route('admin.usuarios') }}" class="block bg-white shadow-md rounded-lg p-6 text-center hover:bg-blue-50 transition transform hover:scale-105">
            <div class="flex justify-center mb-4">
                <svg class="w-16 h-16 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 10-8 0 4 4 0 008 0zM12 14c-4.418 0-8 1.79-8 4v1h16v-1c0-2.21-3.582-4-8-4z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Usuarios</h2>
            <p class="text-gray-600 text-sm mt-2">Gestiona los usuarios registrados.</p>
        </a>

        <!-- Tarjeta Eventos -->
        <a href="{{ route('admin.eventos') }}" class="block bg-white shadow-md rounded-lg p-6 text-center hover:bg-green-50 transition transform hover:scale-105">
            <div class="flex justify-center mb-4">
                <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Eventos</h2>
            <p class="text-gray-600 text-sm mt-2">Crea y administra los eventos.</p>
        </a>

        <!-- Tarjeta Tesorería -->
        <a href="{{ route('admin.ingresos') }}" class="block bg-white shadow-md rounded-lg p-6 text-center hover:bg-yellow-50 transition transform hover:scale-105">
            <div class="flex justify-center mb-4">
                <svg class="w-16 h-16 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c1.104 0 2 .896 2 2s-.896 2-2 2-2-.896-2-2 .896-2 2-2zm0 0V6m0 8v2m-7-7h2m10 0h2m-6-6v2m0 10v2M3 12h2m14 0h2M4 16l1.5 1.5m12-12L20 8m0 8l-1.5 1.5M4 8l1.5-1.5"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800">Tesorería</h2>
            <p class="text-gray-600 text-sm mt-2">Controla los ingresos y gastos.</p>
        </a>

    </div>
</div>
@endsection

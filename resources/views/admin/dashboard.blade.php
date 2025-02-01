@extends('layouts.app') <!-- Extends the main layout -->

@section('content') <!-- Define the content section -->
    <div class="text-center">
        <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
        <p>Bienvenido al panel de administración.</p>
    </div>
        <!-- Aquí puedes agregar más contenido específico para el panel de admin -->
    <div class="bg-white p-4 shadow-md">
        <h2 class="text-xl font-semibold">Últimos usuarios registrados</h2>
        <!-- Aquí puedes agregar una lista o gráfico de usuarios -->
    </div>
@endsection

<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;

// Página principal
Route::get('/', function () {
    return view('welcome');
});

// Rutas para el registro y login de los usuarios
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rutas protegidas que requieren autenticación (por ejemplo, perfil, eventos, etc.)
Route::middleware('auth')->group(function () {
    // Página para ver los eventos y talleres disponibles
    Route::get('/eventos', [EventController::class, 'index'])->name('eventos');

    // Rutas para inscribirse en los eventos
    Route::post('/inscribirse', [InscriptionController::class, 'inscribir'])->name('inscribirse');

    // Redirect GET requests to POST method for "/inscribirse"
    Route::get('/inscribirse', function () {
        return view('welcome');
    });


    // Rutas para el proceso de pago con PayPal
    Route::post('/pago', [PaymentController::class, 'procesarPago']);

    // Perfil del usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Rutas para el Dashboard solo para usuarios autenticados
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::post('/pay-with-paypal', [PaymentController::class, 'payWithPayPal'])->middleware(['auth'])->name('pay.with.paypal');
Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->middleware(['auth'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'paymentCancel'])->middleware(['auth'])->name('payment.cancel');


// Rutas para ADMIN solo para administradores
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Panel de administración
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    // Gestión de ponentes
    Route::get('/ponentes', [AdminController::class, 'listPonentes'])->name('admin.ponentes');
    Route::post('/ponentes', [AdminController::class, 'storePonente']);
    Route::delete('/ponentes/{id}', [AdminController::class, 'destroyPonente']);

    // Gestión de usuarios
    Route::get('/usuarios', [AdminController::class, 'listUsuarios'])->name('admin.usuarios');
    Route::post('/usuarios', [AdminController::class, 'storeUsuario']);
    Route::delete('/usuarios/{id}', [AdminController::class, 'destroyUsuario']);

    // Gestión de eventos
    Route::get('/eventos', [AdminController::class, 'listEventos'])->name('admin.eventos');
    Route::get('/eventos/create', [AdminController::class, 'createEvento'])->name('admin.eventos.create');  // Ruta para mostrar formulario
    Route::post('/eventos', [AdminController::class, 'storeEvento'])->name('admin.eventos.store');  // Ruta para almacenar el evento
    Route::delete('/eventos/{id}', [AdminController::class, 'destroyEvento'])->name('admin.eventos.destroy');

    // Ver ingresos recibidos
    Route::get('/ingresos', [AdminController::class, 'showIngresos'])->name('admin.ingresos');
});

// Ruta de autenticación (debería ser incluida en el archivo auth.php)
require __DIR__ . '/auth.php';

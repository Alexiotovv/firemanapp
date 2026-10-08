<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ParteIncendioController;
use App\Http\Controllers\ParteEmergenciaController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\AvailabilityAdminController;

// Rutas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas (solo autenticadas)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Profile store/update handled by ProfileController
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::put('/profile/{profile}', [ProfileController::class, 'update'])->name('profile.update');
    
    // Rutas para gestión de usuarios
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create')->middleware('admin');
        Route::post('/', [UserController::class, 'store'])->name('store')->middleware('admin');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy')->middleware('admin');
    });
    
    // Rutas para Partes de Incendios
    Route::prefix('partes-incendios')->name('partes-incendios.')->group(function () {
        Route::get('/', [ParteIncendioController::class, 'index'])->name('index');
        Route::get('/create', [ParteIncendioController::class, 'create'])->name('create');
        Route::post('/', [ParteIncendioController::class, 'store'])->name('store');
        Route::get('/{partesIncendio}', [ParteIncendioController::class, 'show'])->name('show');
        Route::get('/{partesIncendio}/edit', [ParteIncendioController::class, 'edit'])->name('edit');
        Route::put('/{partesIncendio}', [ParteIncendioController::class, 'update'])->name('update');
        Route::delete('/{partesIncendio}', [ParteIncendioController::class, 'destroy'])->name('destroy');
        
        // Rutas adicionales
        Route::post('/{partesIncendio}/completar', [ParteIncendioController::class, 'completar'])->name('completar');
        Route::post('/{partesIncendio}/aprobar', [ParteIncendioController::class, 'aprobar'])->name('aprobar');
        Route::get('/{partesIncendio}/imprimir', [ParteIncendioController::class, 'imprimir'])->name('imprimir');
    });
    
    // Disponibilidad (usuarios no admin)
    Route::get('/disponibilidad', [AvailabilityController::class, 'index'])->name('availability.index');
    Route::put('/disponibilidad', [AvailabilityController::class, 'update'])->name('availability.update');

    // Rutas solo para administradores
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/reports', [UserController::class, 'reports'])->name('admin.reports');

        // Emergencias
        Route::get('/admin/emergencias/create', [EmergencyController::class, 'create'])->name('admin.emergencies.create');
        Route::post('/admin/emergencias', [EmergencyController::class, 'store'])->name('admin.emergencies.store');
        Route::get('/admin/emergencias/{emergency}/edit', [EmergencyController::class, 'edit'])->name('admin.emergencies.edit');
        Route::put('/admin/emergencias/{emergency}', [EmergencyController::class, 'update'])->name('admin.emergencies.update');
        Route::patch('/admin/emergencias/{emergency}/estado', [EmergencyController::class, 'updateEstado'])->name('admin.emergencies.estado');
        Route::delete('/admin/emergencias/{emergency}', [EmergencyController::class, 'destroy'])->name('admin.emergencies.destroy');

        // Módulo dinámico de disponibilidad
        Route::prefix('admin/disponibilidad')->name('admin.availability.')->group(function () {
            Route::get('/', [AvailabilityAdminController::class, 'index'])->name('index');
            Route::get('/usuarios/{user}/asignaciones', [AvailabilityAdminController::class, 'userAssignments'])->name('assignments.show');
            Route::post('/usuarios/{user}/categorias/{category}', [AvailabilityAdminController::class, 'toggleAssignment'])->name('assignments.toggle');
            Route::post('/categorias', [AvailabilityAdminController::class, 'storeCategory'])->name('categories.store');
            Route::patch('/categorias/{category}', [AvailabilityAdminController::class, 'toggleCategory'])->name('categories.toggle');
            Route::delete('/categorias/{category}', [AvailabilityAdminController::class, 'destroyCategory'])->name('categories.destroy');
            Route::post('/categorias/{category}/elementos', [AvailabilityAdminController::class, 'storeElement'])->name('elements.store');
            Route::put('/elementos/{element}', [AvailabilityAdminController::class, 'updateElement'])->name('elements.update');
            Route::delete('/elementos/{element}', [AvailabilityAdminController::class, 'destroyElement'])->name('elements.destroy');
        });

        // Exportar usuarios (CSV)
        Route::get('/admin/users/export', [UserController::class, 'export'])->name('admin.users.export');
        
        // Detalle de partes por usuario (vista admin)
        Route::get('/admin/reports/user/{user}', [UserController::class, 'reportUser'])->name('admin.reports.user');
        
        Route::get('/admin/settings', function () {
            return view('admin.settings');
        })->name('admin.settings');
    });
    
});

// Rutas para Partes de Emergencias Médicas (las mismas)
Route::middleware(['auth'])->prefix('partes-emergencias')->name('partes-emergencias.')->group(function () {
    Route::get('/', [ParteEmergenciaController::class, 'index'])->name('index');
    Route::get('/create', [ParteEmergenciaController::class, 'create'])->name('create');
    Route::post('/', [ParteEmergenciaController::class, 'store'])->name('store');
    Route::get('/{partesEmergencia}', [ParteEmergenciaController::class, 'show'])->name('show');
    Route::get('/{partesEmergencia}/edit', [ParteEmergenciaController::class, 'edit'])->name('edit');
    Route::put('/{partesEmergencia}', [ParteEmergenciaController::class, 'update'])->name('update');
    Route::delete('/{partesEmergencia}', [ParteEmergenciaController::class, 'destroy'])->name('destroy');
    
    Route::post('/{partesEmergencia}/completar', [ParteEmergenciaController::class, 'completar'])->name('completar');
    Route::post('/{partesEmergencia}/aprobar', [ParteEmergenciaController::class, 'aprobar'])->name('aprobar');
    Route::get('/{partesEmergencia}/imprimir', [ParteEmergenciaController::class, 'imprimir'])->name('imprimir');
});


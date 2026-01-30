<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ParteIncendioController;

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
    Route::get('/profile', function () {
        return redirect()->route('users.show', auth()->user());
    })->name('profile');
    
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
    
    // Rutas solo para administradores
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/reports', function () {
            return view('admin.reports');
        })->name('admin.reports');
        
        Route::get('/admin/settings', function () {
            return view('admin.settings');
        })->name('admin.settings');
    });
});
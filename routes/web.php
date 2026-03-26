<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SessionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/pacientes/{patient}/sessoes', [SessionController::class, 'store'])->name('sessions.store');
    Route::post('/pacientes/{patient}/sessoes', [SessionController::class, 'store'])->name('sessions.store');
});

# ROTA DE PACIENTES
Route::middleware(['auth'])->group(function () {
    Route::get('/pacientes', [\App\Http\Controllers\PatientController::class, 'index'])->name('pacientes');
    Route::get('/pacientes/novo', [\App\Http\Controllers\PatientController::class, 'create'])->name('patients.create');
    Route::post('/pacientes/salvar', [\App\Http\Controllers\PatientController::class, 'store'])->name('patients.store');
    
    Route::get('/pacientes/{patient}/editar', [\App\Http\Controllers\PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/pacientes/{patient}', [\App\Http\Controllers\PatientController::class, 'update'])->name('patients.update');
    Route::delete('/pacientes/{patient}', [\App\Http\Controllers\PatientController::class, 'destroy'])->name('patients.destroy');

    Route::get('/pacientes/{patient}', [\App\Http\Controllers\PatientController::class, 'show'])->name('patients.show');
    Route::patch('/sessoes/{session}/status', [SessionController::class, 'updateStatus'])->name('sessions.updateStatus');

    Route::patch('/sessions/{session}', [SessionController::class, 'update'])->name('sessions.update');
    Route::delete('/sessions/{session}', [SessionController::class, 'destroy'])->name('sessions.destroy');
    Route::delete('/sessions/{session}/recursive', [SessionController::class, 'destroyRecursive'])->name('sessions.destroyRecursive');

    Route::patch('/pacientes/{patient}/baixar-mes', [PatientController::class, 'marcarMesComoPago'])->name('patients.payMonth');
    Route::patch('/pacientes/{patient}/estornar-mes', [PatientController::class, 'estornarMes'])->name('patients.refundMonth');
});


require __DIR__.'/auth.php';

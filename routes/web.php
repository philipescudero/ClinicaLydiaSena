<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

// CORREÇÃO AQUI: DashboardController::class em vez de DashboardController.php
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/relatorios', [ReportController::class, 'index'])->name('reports.index');
    
    // Removi a duplicata da rota sessions.store que estava aqui
    Route::post('/pacientes/{patient}/sessoes', [SessionController::class, 'store'])->name('sessions.store');
});

# ROTA DE PACIENTES
Route::middleware(['auth'])->group(function () {
    Route::get('/pacientes', [PatientController::class, 'index'])->name('pacientes');
    Route::get('/pacientes/novo', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/pacientes/salvar', [PatientController::class, 'store'])->name('patients.store');
    
    Route::get('/pacientes/{patient}/editar', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/pacientes/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/pacientes/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');

    Route::get('/pacientes/{patient}', [PatientController::class, 'show'])->name('patients.show');
    
    // Sessões e Status
    // Adicione esta linha junto com as outras rotas de sessões
    Route::patch('/sessoes/{session}/realizado', [SessionController::class, 'markPerformed'])->name('sessions.markPerformed');
    Route::patch('/sessoes/{session}/status', [SessionController::class, 'updateStatus'])->name('sessions.updateStatus');
    Route::patch('/sessions/{session}', [SessionController::class, 'update'])->name('sessions.update');
    Route::delete('/sessions/{session}', [SessionController::class, 'destroy'])->name('sessions.destroy');
    Route::delete('/sessions/{session}/recursive', [SessionController::class, 'destroyRecursive'])->name('sessions.destroyRecursive');
    Route::patch('/sessoes/{session}/reverter-realizado', [SessionController::class, 'reversePerformed'])->name('sessions.reversePerformed');
    // Financeiro e WhatsApp
    Route::patch('/pacientes/{patient}/baixar-mes', [PatientController::class, 'marcarMesComoPago'])->name('patients.payMonth');
    Route::patch('/pacientes/{patient}/estornar-mes', [PatientController::class, 'estornarMes'])->name('patients.refundMonth');
    
    // Nova rota para o Log do WhatsApp
    Route::post('/pacientes/{patient}/mark-whatsapp-sent', [PatientController::class, 'markWhatsappSent'])->name('patients.markWhatsappSent');
});

require __DIR__.'/auth.php';
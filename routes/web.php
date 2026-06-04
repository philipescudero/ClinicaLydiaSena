<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProgressNoteController;
use App\Http\Controllers\TreatmentPlanController;
use App\Http\Controllers\AnamneseInfantilController;
use App\Http\Controllers\AdultAnamnesisController;
use App\Http\Controllers\AdultNeuroAnamnesisController;
use App\Http\Controllers\ChildPsicoAnamnesisController;
use App\Http\Controllers\PatientAreaController;
use App\Http\Controllers\SettingController;

// --- AJUSTE AQUI: Redireciona a raiz direto para a tela de login ---
Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
    
    /**
     * AREA DO PACIENTE & PERFIL 
     * Acessível por todos os usuários autenticados
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Se for paciente, ele cai aqui
    Route::get('/meu-espaco', [PatientAreaController::class, 'index'])->name('patient.area');
    // Futura rota do extrato do paciente
    // Route::get('/meu-extrato', [PatientAreaController::class, 'index'])->name('patient.area');

    /**
     * AREA OPERACIONAL (ADMIN)
     * Protegida pela regra 'admin-only' definida no AuthServiceProvider
     */
    Route::middleware(['auth'])->group(function () {  
        
        // Dashboard Principal
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // Validação do PIN Clínico via AJAX
        Route::post('/verificar-pin-clinico', [SettingController::class, 'verifyClinicalPin'])->name('settings.pin.verify');
        
        // Relatórios Gerais
        Route::get('/relatorios', [ReportController::class, 'index'])->name('reports.index');

        # --- GRUPO DE PACIENTES ---
        // 1. Rotas Estáticas
        Route::get('/pacientes', [PatientController::class, 'index'])->name('pacientes');
        Route::get('/pacientes/relatorio-pdf', [PatientController::class, 'gerarRelatorioPdf'])->name('patients.relatorioPdf');
        Route::get('/pacientes/novo', [PatientController::class, 'create'])->name('patients.create');
        Route::post('/pacientes/salvar', [PatientController::class, 'store'])->name('patients.store');
        Route::post('/sessions/fast-store', [SessionController::class, 'storeFast'])->name('sessions.store_fast');

        // 2. Rotas Dinâmicas de Pacientes
        Route::get('/pacientes/{patient}', [PatientController::class, 'show'])->name('patients.show');
        Route::get('/pacientes/{patient}/editar', [PatientController::class, 'edit'])->name('patients.edit');
        Route::put('/pacientes/{patient}', [PatientController::class, 'update'])->name('patients.update');
        Route::delete('/pacientes/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
        
        // 3. Ações Financeiras e Log
        Route::patch('/pacientes/{patient}/baixar-mes', [PatientController::class, 'marcarMesComoPago'])->name('patients.payMonth');
        Route::patch('/pacientes/{patient}/estornar-mes', [PatientController::class, 'estornarMes'])->name('patients.refundMonth');
        Route::post('/pacientes/{patient}/mark-whatsapp-sent', [PatientController::class, 'markWhatsappSent'])->name('patients.markWhatsappSent');
        Route::delete('/pagamentos-registro/{id}', [App\Http\Controllers\PatientController::class, 'destroyPayment'])->name('payments.destroyIndividual');
        # --- SESSÕES E NOTAS ---
        // ... (Mantidas as rotas internas de sessões)
        Route::put('/sessoes/{session}', [SessionController::class, 'update'])->name('sessions.update');
        Route::delete('/sessoes/{session}', [SessionController::class, 'destroy'])->name('sessions.destroy');
        Route::post('/pacientes/{patient}/sessoes', [SessionController::class, 'store'])->name('sessions.store');
        Route::post('/patients/{patient}/notes', [ProgressNoteController::class, 'store'])->name('notes.store');
        Route::put('/notes/{note}', [ProgressNoteController::class, 'update'])->name('notes.update');
        Route::delete('/notes/{note}', [ProgressNoteController::class, 'destroy'])->name('notes.destroy');
        Route::patch('/pacientes/{patient}/baixar-parcial', [SessionController::class, 'storePartialPayment'])->name('sessions.pay_partial');
        Route::patch('/pacientes/{patient}/baixar-parcial-reais', [App\Http\Controllers\SessionController::class, 'storePartialPaymentInReais']);

        Route::patch('/sessoes/{session}/realizado', [SessionController::class, 'markPerformed'])->name('sessions.markPerformed');
        Route::patch('/sessoes/{session}/reverter-realizado', [SessionController::class, 'reversePerformed'])->name('sessions.reversePerformed');
        Route::patch('/sessoes/{session}/status', [SessionController::class, 'updateStatus'])->name('sessions.updateStatus');
        Route::patch('/sessions/{session}', [SessionController::class, 'update'])->name('sessions.update');
        Route::delete('/sessions/{session}', [SessionController::class, 'destroy'])->name('sessions.destroy');
        Route::delete('/sessions/{session}/recursive', [SessionController::class, 'destroyRecursive'])->name('sessions.destroyRecursive');

        # --- PLANO TERAPÊUTICO ---
        Route::get('/pacientes/{patient}/plano-terapeutico', [TreatmentPlanController::class, 'index'])->name('patients.plan');
        Route::post('/pacientes/{patient}/plano-terapeutico', [TreatmentPlanController::class, 'store'])->name('patients.plan.store');
        Route::get('/pacientes/{patient}/plano-terapeutico-pdf', [TreatmentPlanController::class, 'exportPdf'])->name('patients.plan.pdf');
        Route::put('/plano-terapeutico/{plan}', [TreatmentPlanController::class, 'update'])->name('patients.plan.update');
        Route::delete('/plano-terapeutico/{plan}', [TreatmentPlanController::class, 'destroy'])->name('patients.plan.destroy');

        # --- ANAMNESES ---
        // Infantil Neuro
        Route::get('/pacientes/{patient}/anamnese-neuro-infantil', [AnamneseInfantilController::class, 'index'])->name('anamnese.neuro.infantil');
        Route::post('/pacientes/{patient}/anamnese-neuro-infantil', [AnamneseInfantilController::class, 'store'])->name('anamnese.neuro.infantil.store');
        Route::get('/pacientes/{patient}/anamnese-pdf', [AnamneseInfantilController::class, 'exportPdf'])->name('anamnese.pdf');
        Route::delete('/anamnese-neuro-infantil/{anamnesis}', [AnamneseInfantilController::class, 'destroy'])->name('anamnese.neuro.infantil.destroy');

        // Adulto Neuro
        Route::get('/pacientes/{patient}/anamnese-neuro-adulto', [AdultNeuroAnamnesisController::class, 'index'])->name('anamnese.neuro.adulto');
        Route::post('/pacientes/{patient}/anamnese-neuro-adulto', [AdultNeuroAnamnesisController::class, 'store'])->name('anamnese.neuro.adulto.store');
        Route::get('/pacientes/{patient}/anamnese-neuro-adulto-pdf', [AdultNeuroAnamnesisController::class, 'exportPdf'])->name('anamnese.neuro.adulto.pdf');
        Route::delete('/anamnese-neuro-adulto/{id}', [AdultNeuroAnamnesisController::class, 'destroy'])->name('anamnese.neuro.adulto.destroy');

        // Infantil Psico
        Route::get('/pacientes/{patient}/anamnese-psico-infantil', [ChildPsicoAnamnesisController::class, 'index'])->name('anamnese.psico.infantil');
        Route::post('/pacientes/{patient}/anamnese-psico-infantil', [ChildPsicoAnamnesisController::class, 'store'])->name('anamnese.psico.infantil.store');
        Route::get('/pacientes/{patient}/anamnese-psico-infantil-pdf', [ChildPsicoAnamnesisController::class, 'exportPdf'])->name('anamnese.psico.infantil.pdf');
        Route::delete('/anamnese-psico-infantil/{id}', [ChildPsicoAnamnesisController::class, 'destroy'])->name('anamnese.psico.infantil.destroy');

        // Adulto Psico
        Route::get('/pacientes/{patient}/anamnese-adulto', [AdultAnamnesisController::class, 'index'])->name('anamnese.psico.adulto');
        Route::post('/pacientes/{patient}/anamnese-adulto', [AdultAnamnesisController::class, 'store'])->name('anamnese.psico.adulto.store');
        Route::get('/pacientes/{patient}/anamnese-adulto-pdf', [AdultAnamnesisController::class, 'exportPdf'])->name('anamnese.psico.adulto.pdf');
        Route::delete('/anamnese-adulto/{anamnesis}', [AdultAnamnesisController::class, 'destroy'])->name('anamnese.psico.adulto.destroy');

        # --- CONFIGURAÇÕES ---
        // Página principal de configurações
        Route::get('/configuracoes', [SettingController::class, 'index'])->name('settings.index');

        // Ação de trocar senha em massa
        Route::post('/configuracoes/senha-global', [SettingController::class, 'updateGlobalPassword'])->name('settings.password.global');

        Route::get('/configuracoes/clinica', [SettingController::class, 'editClinic'])->name('settings.clinic.edit');
        Route::post('/configuracoes/clinica', [SettingController::class, 'updateClinicData'])->name('settings.clinic.update');
        
        Route::get('/configuracoes/equipe', [SettingController::class, 'indexAdmins'])->name('settings.admins.index');
        Route::post('/configuracoes/equipe', [SettingController::class, 'storeAdmin'])->name('settings.admins.store');
        Route::delete('/configuracoes/equipe/{id}', [SettingController::class, 'destroyAdmin'])->name('settings.admins.destroy');
        Route::get('/configuracoes/backup', [SettingController::class, 'generateBackup'])->name('settings.backup');

        # --- SISTEMA DE ENTRADAS CONSOLIDADAS (PROPOSTA B) ---
        // Disparado pela modal flutuante da listagem para criar o recibo individual de caixa
        Route::post('/patients/{patient}/pay-integral', [PatientController::class, 'registrarPagamentoIntegral'])->name('patients.pay-integral');
    });
});

require __DIR__.'/auth.php';
<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientSession;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // --- NAVEGAÇÃO DE MÊS ---
        $mesRef = $request->has('mes') ? $request->mes : Carbon::now()->month;
        $anoRef = $request->has('ano') ? $request->ano : Carbon::now()->year;
        
        $inicioMes = Carbon::createFromDate($anoRef, $mesRef, 1)->startOfMonth();
        $fimMes = $inicioMes->copy()->endOfMonth();

        // --- NAVEGAÇÃO DE SEMANA ---
        $dataFoco = $request->has('semana') ? Carbon::parse($request->semana) : Carbon::now();
        
        // Garante que a semana comece na Segunda-feira
        $inicioSemana = $dataFoco->copy()->startOfWeek(Carbon::MONDAY);
        $fimSemana = $inicioSemana->copy()->endOfWeek(Carbon::SUNDAY);

        $hoje = Carbon::today();

        // --- 1. FINANCEIRO (Cards do topo) ---
        $faturamentoPrevisto = PatientSession::whereBetween('session_date', [$inicioMes, $fimMes])->sum('value');
        $faturamentoRealizado = PatientSession::whereBetween('session_date', [$inicioMes, $fimMes])
            ->where('status', 'pago')
            ->sum('value');

        // --- 2. APROVEITAMENTO DAS SESSÕES ---
        $totalSessoesDoMes = PatientSession::whereBetween('session_date', [$inicioMes, $fimMes])->count();
        
        $sessoesRealizadasDoMes = PatientSession::whereBetween('session_date', [$inicioMes, $fimMes])
            ->where('performed', true)
            ->count();

        $porcentagemConclusao = $totalSessoesDoMes > 0 ? ($sessoesRealizadasDoMes / $totalSessoesDoMes) * 100 : 0;

        // --- 3. GPS SEMANAL (Próximos 7 dias) ---
        $sessoesSemana = PatientSession::with('patient')
            ->whereBetween('session_date', [$hoje->copy()->startOfDay(), $hoje->copy()->addDays(7)->endOfDay()])
            ->orderBy('session_date', 'asc')->get();

        // --- 4. OPERAÇÕES DA SEMANA (Substitui Operações do Mês) ---
        // Agora filtramos apenas as sessões da semana selecionada para visualização assertiva
        $sessoesOperacoes = PatientSession::with('patient')
            ->whereBetween('session_date', [
                $inicioSemana->copy()->startOfDay(), 
                $inicioSemana->copy()->addDays(4)->endOfDay() // Segunda a Sexta
            ])
            ->orderBy('session_date', 'asc')
            ->get();

        $pacientesNoMes = PatientSession::whereBetween('session_date', [$inicioMes, $fimMes])
            ->distinct('patient_id')->count('patient_id');

        // --- 5. MAPA DE HORÁRIOS ---
        $sessoesGrade = PatientSession::with('patient')
            ->whereBetween('session_date', [$inicioSemana, $fimSemana])
            ->get();

        $gradeHorarios = [];
        foreach ($sessoesGrade as $sessao) {
            $dt = Carbon::parse($sessao->session_date);
            $gradeHorarios[$dt->format('Y-m-d')][$dt->format('H:00')][] = $sessao;
        }

        $horariosPermitidos = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
        $todosPacientes = Patient::orderBy('name', 'asc')->get();

        // Adicionada a variável 'sessoesOperacoes' ao compact
        return view('dashboard', compact(
            'faturamentoPrevisto', 'faturamentoRealizado', 'sessoesSemana', 'sessoesOperacoes', 
            'pacientesNoMes', 'totalSessoesDoMes', 'sessoesRealizadasDoMes', 'porcentagemConclusao',
            'gradeHorarios', 'horariosPermitidos', 'inicioSemana', 'inicioMes', 'todosPacientes'
        ));
    }
}
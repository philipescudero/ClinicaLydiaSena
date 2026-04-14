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
        $inicioSemana = $dataFoco->copy()->startOfWeek();
        $fimSemana = $dataFoco->copy()->endOfWeek();

        $hoje = Carbon::today();

        // --- 1. FINANCEIRO (Cards Rosa e Verde do topo) ---
        $faturamentoPrevisto = PatientSession::whereBetween('session_date', [$inicioMes, $fimMes])->sum('value');
        $faturamentoRealizado = PatientSession::whereBetween('session_date', [$inicioMes, $fimMes])
            ->where('status', 'pago')
            ->sum('value');

        // --- 2. NOVO: APROVEITAMENTO DAS SESSÕES (Cálculo do erro) ---
        $totalSessoesDoMes = PatientSession::whereBetween('session_date', [$inicioMes, $fimMes])->count();
        
        $sessoesRealizadasDoMes = PatientSession::whereBetween('session_date', [$inicioMes, $fimMes])
            ->where('performed', true)
            ->count();

        $porcentagemConclusao = $totalSessoesDoMes > 0 ? ($sessoesRealizadasDoMes / $totalSessoesDoMes) * 100 : 0;

        // --- 3. GPS SEMANAL (GPS Esquerdo) ---
        $sessoesSemana = PatientSession::with('patient')
            ->whereBetween('session_date', [$hoje, $hoje->copy()->addDays(7)])
            ->orderBy('session_date', 'asc')->get();

        // --- 4. OPERAÇÕES DO MÊS (Tabela Direita) ---
        $sessoesMes = PatientSession::with('patient')
            ->whereBetween('session_date', [$inicioMes, $fimMes])
            ->orderBy('session_date', 'asc')->get();

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

        return view('dashboard', compact(
            'faturamentoPrevisto', 'faturamentoRealizado', 'sessoesSemana', 'sessoesMes', 
            'pacientesNoMes', 'totalSessoesDoMes', 'sessoesRealizadasDoMes', 'porcentagemConclusao',
            'gradeHorarios', 'horariosPermitidos', 'inicioSemana', 'inicioMes', 'todosPacientes'
        ));
    }
}
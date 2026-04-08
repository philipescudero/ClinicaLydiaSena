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
        // --- DATAS DE REFERÊNCIA E NAVEGAÇÃO ---
        $hoje = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();
        $fimMes = Carbon::now()->endOfMonth();
        
        // Captura a data da URL para navegar nas semanas
        $dataFoco = $request->has('semana') ? Carbon::parse($request->semana) : Carbon::now();
        $inicioSemana = $dataFoco->copy()->startOfWeek();
        $fimSemana = $dataFoco->copy()->endOfWeek();

        // --- 1. FINANCEIRO (Cards do Topo) ---
        $faturamentoPrevisto = PatientSession::whereBetween('session_date', [$inicioMes, $fimMes])->sum('value');
        $faturamentoRealizado = PatientSession::whereBetween('session_date', [$inicioMes, $fimMes])
            ->where('status', 'pago')
            ->sum('value');

        // --- 2. AGENDA DA SEMANA (Painel GPS Esquerdo) ---
        $sessoesSemana = PatientSession::with('patient')
            ->whereBetween('session_date', [$hoje, $hoje->copy()->addDays(7)])
            ->orderBy('session_date', 'asc')->get();

        // --- 3. AGENDA DO MÊS (Painel Operações Direito) ---
        $sessoesMes = PatientSession::with('patient')
            ->whereBetween('session_date', [$inicioMes, $fimMes])
            ->orderBy('session_date', 'asc')->get();

        $pacientesNoMes = PatientSession::whereBetween('session_date', [$inicioMes, $fimMes])
            ->distinct('patient_id')
            ->count('patient_id');
            
        $totalSessoesDoMes = PatientSession::whereBetween('session_date', [$inicioMes, $fimMes])->count();

        // --- 4. MAPA DE HORÁRIOS (Grade Visual Compacta) ---
        $sessoesGrade = PatientSession::with('patient')
            ->whereBetween('session_date', [$inicioSemana, $fimSemana])
            ->get();

        $gradeHorarios = [];
        foreach ($sessoesGrade as $sessao) {
            $dt = Carbon::parse($sessao->session_date);
            $data = $dt->format('Y-m-d');
            // Arredonda a hora para "cheia" para garantir que 15:30 apareça no slot das 15:00
            $hora = $dt->format('H:00'); 
            $gradeHorarios[$data][$hora] = $sessao;
        }

        $horariosPermitidos = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];

        // --- RETORNO ÚNICO ---
        return view('dashboard', compact(
            'faturamentoPrevisto', 
            'faturamentoRealizado', 
            'sessoesSemana', 
            'sessoesMes', 
            'pacientesNoMes', 
            'totalSessoesDoMes',
            'gradeHorarios', 
            'horariosPermitidos', 
            'inicioSemana',
            'dataFoco'
        ));
    }
}
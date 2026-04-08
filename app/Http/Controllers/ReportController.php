<?php

namespace App\Http\Controllers;

use App\Models\PatientSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
        public function index()
    {
        $seisMesesAtras = Carbon::now()->subMonths(6)->startOfMonth();

        // 1. Faturamento Mensal (Mantendo a lógica anterior que funcionou)
        $faturamentoMensal = PatientSession::select(
                DB::raw('SUM(value) as total'),
                DB::raw("DATE_FORMAT(session_date, '%m/%Y') as mes"),
                DB::raw("DATE_FORMAT(session_date, '%Y-%m') as mes_referencia")
            )
            ->where('status', 'pago')
            ->where('session_date', '>=', $seisMesesAtras)
            ->groupBy('mes_referencia', 'mes')
            ->orderBy('mes_referencia', 'asc')
            ->get();

        // 2. NOVA LOGICA: Assiduidade Mensal (Agendadas vs Realizadas)
        $assiduidadeMensal = PatientSession::select(
                DB::raw("DATE_FORMAT(session_date, '%m/%Y') as mes"),
                DB::raw("DATE_FORMAT(session_date, '%Y-%m') as mes_referencia"),
                DB::raw("COUNT(*) as agendadas"),
                DB::raw("SUM(CASE WHEN performed = 1 THEN 1 ELSE 0 END) as realizadas")
            )
            ->where('session_date', '>=', $seisMesesAtras)
            ->where('session_date', '<=', Carbon::now()->endOfMonth()) // Alterado para pegar o mês todo
            ->groupBy('mes_referencia', 'mes')
            ->orderBy('mes_referencia', 'asc')
            ->get();

        return view('reports.index', compact('faturamentoMensal', 'assiduidadeMensal'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\PatientSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Captura o ano da URL ou usa o ano atual como padrão
        $anoFoco = $request->get('ano', Carbon::now()->year);

        // 2. Faturamento Mensal (Líquido) filtrado pelo Ano
        $faturamentoMensal = PatientSession::select(
                DB::raw('SUM(value) as total'),
                DB::raw("DATE_FORMAT(session_date, '%m/%Y') as mes"),
                DB::raw("DATE_FORMAT(session_date, '%m') as mes_num")
            )
            ->where('status', 'pago')
            ->whereYear('session_date', $anoFoco)
            ->groupBy('mes', 'mes_num')
            ->orderBy('mes_num', 'asc')
            ->get();

        // 3. Assiduidade Mensal (Agendadas vs Realizadas) filtrada pelo Ano
        $assiduidadeMensal = PatientSession::select(
                DB::raw("DATE_FORMAT(session_date, '%m/%Y') as mes"),
                DB::raw("DATE_FORMAT(session_date, '%m') as mes_num"),
                DB::raw("COUNT(*) as agendadas"),
                DB::raw("SUM(CASE WHEN performed = 1 THEN 1 ELSE 0 END) as realizadas")
            )
            ->whereYear('session_date', $anoFoco)
            ->groupBy('mes', 'mes_num')
            ->orderBy('mes_num', 'asc')
            ->get();

        // Enviamos também o anoFoco para a View saber qual botão destacar
        return view('reports.index', compact('faturamentoMensal', 'assiduidadeMensal', 'anoFoco'));
    }
}
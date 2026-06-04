<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientSession; // CORREÇÃO: Alinhado para usar a model correta da agenda
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PatientAreaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $patient = Patient::where('cpf', $user->email)->first();

        if (!$patient) {
            abort(404, 'Registro clínico de paciente não encontrado.');
        }

        // Captura mês e ano da URL ou usa o atual
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Criamos um objeto de data para facilitar a exibição e navegação
        $currentDate = \Carbon\Carbon::createFromDate($year, $month, 1);

        // Carrega todas as sessões do mês selecionado usando a model correta
        $allSessions = PatientSession::where('patient_id', $patient->id)
            ->whereMonth('session_date', $month)
            ->whereYear('session_date', $year)
            ->orderBy('session_date', 'asc')
            ->get();

        // 1. Soma o valor bruto total gerado pelas sessões de competência do mês escolhido
        $totalAmount = $allSessions->sum('value');

        // 2. Soma tudo o que o paciente já pagou para este respectivo mês de referência na tabela payments
        $totalPagoEmRecibos = Payment::where('patient_id', $patient->id)
            ->where('reference_month', $month)
            ->where('reference_year', $year)
            ->sum('amount');

        // 3. Calcula o valor líquido que ele de fato ainda deve pagar
        $restantePendente = $totalAmount - $totalPagoEmRecibos;
        $restantePendente = $restantePendente > 0 ? $restantePendente : 0;

        // Define o status booleano esperado pelo botão Pix e badges do front-end
        $isPaid = ($restantePendente <= 0 && $totalAmount > 0);

        // 4. Coleta o histórico de entradas unitárias deste mês de competência para renderizar o Extrato
        $historicoPagamentos = Payment::where('patient_id', $patient->id)
            ->where('reference_month', $month)
            ->where('reference_year', $year)
            ->orderBy('payment_date', 'desc')
            ->get();

        return view('patients.area_paciente', compact(
            'allSessions', 
            'totalAmount',
            'restantePendente',
            'currentDate',
            'isPaid',
            'historicoPagamentos',
            'month',
            'year'
        ));
    }
}
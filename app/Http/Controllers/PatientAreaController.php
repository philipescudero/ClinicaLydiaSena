<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session; // Certifique-se de que a model mapeia a tabela correta
use App\Models\Patient;
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

        // Carrega todas as sessões do mês selecionado
        $allSessions = Session::where('patient_id', $patient->id)
            ->whereMonth('session_date', $month)
            ->whereYear('session_date', $year)
            ->orderBy('session_date', 'asc')
            ->get();

        // Calcula o faturamento total do mês
        $totalAmount = $allSessions->sum('value');

        // VERIFICAÇÃO INTELIGENTE: Está pago se houver sessões E nenhuma delas estiver pendente
        $isPaid = $allSessions->isNotEmpty() && !$allSessions->where('status', 'pendente')->isNotEmpty();

        return view('patients.area_paciente', compact(
            'allSessions', 
            'totalAmount',
            'currentDate',
            'isPaid' // <--- Nova variável limpa enviada para a View
        ));
    }
}
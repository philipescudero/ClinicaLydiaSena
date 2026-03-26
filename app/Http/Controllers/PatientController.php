<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('mes', now()->month);
        $year = $request->get('ano', now()->year);
        $search = $request->get('search');
        $filter = $request->get('filter', 'todos');

        $query = Patient::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($filter === 'agendados') {
            $query->whereHas('sessions', function($q) use ($month, $year) {
                $q->whereMonth('session_date', $month)
                  ->whereYear('session_date', $year);
            });
        }

        $patients = $query->withCount([
            'sessions as total_sessoes_mes' => function($q) use ($month, $year) {
                $q->whereMonth('session_date', $month)
                ->whereYear('session_date', $year);
            },
            'sessions as s1_count' => function($q) use ($month, $year) { 
                $q->whereMonth('session_date', $month)->whereYear('session_date', $year)->where('service_type', 1); 
            },
            'sessions as s2_count' => function($q) use ($month, $year) { 
                $q->whereMonth('session_date', $month)->whereYear('session_date', $year)->where('service_type', 2); 
            },
            'sessions as s3_count' => function($q) use ($month, $year) { 
                $q->whereMonth('session_date', $month)->whereYear('session_date', $year)->where('service_type', 3); 
            },
            // Conta se há pendências no mês
            'sessions as pendentes_no_mes' => function($q) use ($month, $year) {
                $q->whereMonth('session_date', $month)
                  ->whereYear('session_date', $year)
                  ->where('status', 'pendente');
            },
        ])
        ->withSum(['sessions as s1_sum' => function($q) use ($month, $year) { 
            $q->whereMonth('session_date', $month)->whereYear('session_date', $year)->where('service_type', 1); 
        }], 'value')
        ->withSum(['sessions as s2_sum' => function($q) use ($month, $year) { 
            $q->whereMonth('session_date', $month)->whereYear('session_date', $year)->where('service_type', 2); 
        }], 'value')
        ->withSum(['sessions as s3_sum' => function($q) use ($month, $year) { 
            $q->whereMonth('session_date', $month)->whereYear('session_date', $year)->where('service_type', 3); 
        }], 'value')
        ->withSum(['sessions as total_mes' => function($q) use ($month, $year) {
            $q->whereMonth('session_date', $month)->whereYear('session_date', $year);
        }], 'value')
        ->get();

        return view('pacientes', compact('patients', 'month', 'year', 'search', 'filter'));
    }

    // MÉTODO PARA DAR BAIXA EM TODAS AS SESSÕES DO MÊS
    public function marcarMesComoPago(Request $request, Patient $patient)
    {
        $patient->sessions()
            ->whereMonth('session_date', $request->mes)
            ->whereYear('session_date', $request->ano)
            ->where('status', 'pendente')
            ->update(['status' => 'pago']);

        return redirect()->back()->with('success', 'Pagamentos de ' . $patient->name . ' atualizados!');
    }

    public function show(Request $request, Patient $patient)
    {
        $month = $request->get('mes', now()->month);
        $year = $request->get('ano', now()->year);

        $sessions = $patient->sessions()
            ->whereYear('session_date', $year)
            ->whereMonth('session_date', $month)
            ->orderBy('session_date', 'desc')
            ->get();

        return view('patients.show', compact('patient', 'sessions', 'month', 'year'));
    }

    public function create() { return view('patients.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email',
            'cpf'  => 'required|string|max:14',
            'phone' => 'required',
        ]);
        Patient::create($request->all());
        return redirect()->route('pacientes')->with('success', 'Paciente cadastrado com sucesso!');
    }

    public function edit(Patient $patient) { return view('patients.edit', compact('patient')); }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:patients,email,' . $patient->id,
            'cpf'  => 'required|string|max:14',
            'phone' => 'nullable|string',
        ]);
        $patient->update($request->all());
        return redirect()->route('pacientes')->with('success', 'Dados atualizados!');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('pacientes')->with('success', 'Paciente removido.');
    }
    public function estornarMes(Request $request, Patient $patient)
    {
        $patient->sessions()
            ->whereMonth('session_date', $request->mes)
            ->whereYear('session_date', $request->ano)
            ->where('status', 'pago')
            ->update(['status' => 'pendente']);

        return redirect()->back()->with('success', 'Pagamentos de ' . $patient->name . ' revertidos para pendente.');
    }
    public function markWhatsappSent(Request $request, Patient $patient)
    {
        $key = $request->ano . '-' . str_pad($request->mes, 2, '0', STR_PAD_LEFT);
        $logs = $patient->whatsapp_check_log ?? [];
        $logs[$key] = true;

        $patient->update(['whatsapp_check_log' => $logs]);

        return response()->json(['success' => true]);
    }
}
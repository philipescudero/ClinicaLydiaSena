<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
        public function index(Request $request)
        {
            // 1. Pegamos os filtros da URL
            $month = $request->get('mes', now()->month);
            $year = $request->get('ano', now()->year);
            $search = $request->get('search');
            $filter = $request->get('filter', 'todos'); // Padrão é mostrar todos

            // 2. Iniciamos a Query com os filtros de busca (se houver)
            $query = Patient::query();

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // NOVO: Filtro de "Apenas Agendados"
            if ($filter === 'agendados') {
                $query->whereHas('sessions', function($q) use ($month, $year) {
                    $q->whereMonth('session_date', $month)
                    ->whereYear('session_date', $year);
                });
            }

            // 3. Adicionamos as contagens e somas filtradas por mês/ano
            $patients = $query->withCount([
                'sessions as s1_count' => function($q) use ($month, $year) { 
                    $q->whereMonth('session_date', $month)->whereYear('session_date', $year)->where('service_type', 1); 
                },
                'sessions as s2_count' => function($q) use ($month, $year) { 
                    $q->whereMonth('session_date', $month)->whereYear('session_date', $year)->where('service_type', 2); 
                },
                'sessions as s3_count' => function($q) use ($month, $year) { 
                    $q->whereMonth('session_date', $month)->whereYear('session_date', $year)->where('service_type', 3); 
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
            ->get(); // Agora sim, executamos a consulta final

            return view('pacientes', compact('patients', 'month', 'year', 'search'));
        }

        // ... os outros métodos (show, create, store, etc) permanecem iguais ...
    public function show(Request $request, Patient $patient)
    {
        // Pegamos o mês e ano da URL, ou usamos o atual como padrão
        $month = $request->get('mes', now()->month);
        $year = $request->get('ano', now()->year);

        // Filtramos as sessões apenas daquele mês/ano específico
        $sessions = $patient->sessions()
            ->whereYear('session_date', $year)
            ->whereMonth('session_date', $month)
            ->orderBy('session_date', 'desc')
            ->get(); // Aqui não usamos paginate(10), pois queremos o mês inteiro na tela

        return view('patients.show', compact('patient', 'sessions', 'month', 'year'));
    }
    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email',
            'cpf'  => 'required|string|max:14', // CPF obrigatório
            'phone' => 'required',
        ]);

        Patient::create($request->all());

        return redirect()->route('pacientes')->with('success', 'Paciente cadastrado com sucesso!');
    }
    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:patients,email,' . $patient->id,
            'cpf'  => 'required|string|max:14', // CPF obrigatório
            'phone' => 'nullable|string',
            // Adicione validações extras aqui se quiser
        ]);

        // O $request->all() pega tudo que veio do formulário (incluindo cidade e observações)
        $patient->update($request->all());

        return redirect()->route('pacientes')->with('success', 'Dados de ' . $patient->name . ' atualizados com sucesso!');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('pacientes')->with('success', 'Paciente removido com sucesso.');
    }
    
}

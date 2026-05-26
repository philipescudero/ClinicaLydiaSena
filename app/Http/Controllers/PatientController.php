<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        // Padronizado para 'month' e 'year' conforme a View
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
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

        // Adiciona a injeção da lista de sessões pendentes no formato string mapeada
        $patients = $query->select('*')
        ->selectRaw("
            (SELECT GROUP_CONCAT(DATE_FORMAT(session_date, '%d/%m às %H:%i') ORDER BY session_date ASC SEPARATOR '|') 
             FROM patient_sessions 
             WHERE patient_sessions.patient_id = patients.id 
               AND MONTH(session_date) = ? 
               AND YEAR(session_date) = ? 
               AND performed = 0) as sessoes_pendentes
        ", [$month, $year])
        ->withCount([
            'sessions as total_sessoes_mes' => function($q) use ($month, $year) {
                $q->whereMonth('session_date', $month)->whereYear('session_date', $year);
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
        ->orderBy('name', 'asc')
        ->get();

        return view('pacientes', compact('patients', 'month', 'year', 'search', 'filter'));
    }

    public function gerarRelatorioPdf(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $patients = Patient::select('*')
        ->selectRaw("
            (SELECT GROUP_CONCAT(DATE_FORMAT(session_date, '%d/%m às %H:%i') ORDER BY session_date ASC SEPARATOR '|') 
             FROM patient_sessions 
             WHERE patient_sessions.patient_id = patients.id 
               AND MONTH(session_date) = ? 
               AND YEAR(session_date) = ? 
               AND performed = 0) as sessoes_pendentes
        ", [$month, $year])
        ->withCount([
            'sessions as total_sessoes_mes' => function($q) use ($month, $year) {
                $q->whereMonth('session_date', $month)->whereYear('session_date', $year);
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
            'sessions as pendentes_no_mes' => function($q) use ($month, $year) {
                $q->whereMonth('session_date', $month)->whereYear('session_date', $year)->where('status', 'pendente');
            },
        ])
        ->withSum(['sessions as total_mes' => function ($q) use ($month, $year) {
            $q->whereMonth('session_date', $month)->whereYear('session_date', $year);
        }], 'value')
        ->orderBy('name', 'asc')
        ->get();

        // --- INSTANCIAÇÃO DIRETA DO DOMPDF NATIVO (Ignora as travas do ServiceProvider) ---
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', '/home1/hg477223'); // Dá acesso seguro aos arquivos da sua conta cPanel

        $dompdf = new \Dompdf\Dompdf($options);

        // Renderiza o HTML da View manualmente em formato string de memória
        $html = view('pdf.relatorio-mensal', compact('patients', 'month', 'year'))->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Dispara o download binário direto para o navegador do usuário
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Relatorio-Lydia-Sena-'.$month.'-'.$year.'.pdf"',
        ]);
    }

    // MÉTODO PARA DAR BAIXA EM TODAS AS SESSÕES DO MÊS
    public function marcarMesComoPago(Request $request, Patient $patient)
    {
        $patient->sessions()
            ->whereMonth('session_date', $request->month)
            ->whereYear('session_date', $request->year)
            ->update(['status' => 'pago']);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pagamento registrado!']);
        }

        return redirect()->back()->with('success', 'Pagamentos de ' . $patient->name . ' atualizados!');
    }

    public function show(Patient $patient, Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $patient->load(['childNeuroAnamnesis', 'adultAnamnesis', 'adultNeuroAnamnesis', 'childPsicoAnamnesis']);
        
        $sessions = $patient->sessions()
            ->whereYear('session_date', $year)
            ->whereMonth('session_date', $month)
            ->orderBy('session_date', 'desc')
            ->get();

        $progressNotes = $patient->progressNotes()
            ->orderBy('session_date', 'desc')
            ->paginate(5, ['*'], 'notes_page'); 

        $clinicalAuthorized = session('clinical_access_authorized', false);

        return view('patients.show', compact('patient', 'progressNotes', 'sessions', 'month', 'year', 'clinicalAuthorized'));
    }

    public function create() { return view('patients.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf'  => 'required|string|max:14|unique:patients,cpf|unique:users,email',
            'email' => 'nullable|email',
        ], [
            'cpf.unique' => 'Este CPF já está cadastrado no sistema (seja como paciente ou acesso).'
        ]);

        $patient = Patient::create($request->all());

        \App\Models\User::create([
            'name'     => $patient->name,
            'email'    => $patient->cpf, 
            'password' => \Illuminate\Support\Facades\Hash::make('clinicalydiasena'),
            'role'     => 'patient',
        ]);

        return redirect()->route('pacientes')->with('success', 'Paciente e acesso criados com sucesso!');
    }

    public function edit(Patient $patient) { return view('patients.edit', compact('patient')); }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf'  => 'required|string|unique:patients,cpf,' . $id, 
        ], [
            'cpf.unique' => 'Este CPF já pertence a outro paciente cadastrado.',
        ]);

        $patient = Patient::findOrFail($id);
        
        if ($patient->cpf !== $request->cpf) {
            $user = User::where('email', $patient->cpf)->first();
            if ($user) {
                $user->update(['email' => $request->cpf]);
            }
        }

        $patient->update($request->all());

        return redirect()->route('pacientes')->with('success', 'Registro updated com sucesso!');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('pacientes')->with('success', 'Paciente removido.');
    }

    public function estornarMes(Request $request, Patient $patient)
    {
        $patient->sessions()
            ->whereMonth('session_date', $request->month)
            ->whereYear('session_date', $request->year)
            ->update(['status' => 'pendente']);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pagamento estornado!']);
        }

        return redirect()->back()->with('success', 'Pagamentos de ' . $patient->name . ' revertidos.');
    }

    public function markWhatsappSent(Request $request, Patient $patient)
    {
        $key = $request->year . '-' . str_pad($request->month, 2, '0', STR_PAD_LEFT);
        $logs = $patient->whatsapp_check_log ?? [];
        $logs[$key] = true;

        $patient->update(['whatsapp_check_log' => $logs]);

        return response()->json(['success' => true]);
    }

    public function sessionsStore(Request $request, Patient $patient)
    {
        $request->validate([
            'date_only' => 'required|date',
            'time_only' => 'required',
            'service_type' => 'required',
            'value' => 'required|numeric',
        ]);

        $dataCompleta = $request->date_only . ' ' . $request->time_only . ':00';

        $patient->sessions()->create([
            'session_date' => $dataCompleta,
            'service_type' => $request->service_type,
            'value' => $request->value,
            'notes' => $request->notes,
            'status' => 'pendente',
        ]);

        return redirect()->back()->with('success', 'Atendimento salvo com sucesso!');
    }

    public function payMonth(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        
        DB::table('patient_payments')->updateOrInsert(
            [
                'patient_id' => $patient->user_id, 
                'month'      => $request->month,
                'year'       => $request->year,
            ],
            [
                'amount'     => $patient->total_mes,
                'paid'       => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return back()->with('success', 'Pagamento registrado com sucesso!');
    }

    public function refundMonth(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        DB::table('patient_sessions')
            ->where('patient_id', $id)
            ->whereMonth('session_date', $request->month)
            ->whereYear('session_date', $request->year)
            ->update(['performed' => false]);

        return back()->with('success', 'Pagamentos revertidos para pendente.');
    }
}
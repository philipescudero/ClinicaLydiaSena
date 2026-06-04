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

        // CORREÇÃO CRÍTICA: sessoes_pendentes agora busca por status = 'pendente'
        $patients = $query->select('*')
        ->selectRaw("
            (SELECT GROUP_CONCAT(DATE_FORMAT(session_date, '%d/%m às %H:%i') ORDER BY session_date ASC SEPARATOR '|') 
             FROM patient_sessions 
             WHERE patient_sessions.patient_id = patients.id 
               AND MONTH(session_date) = ? 
               AND YEAR(session_date) = ? 
               AND status = 'pendente') as sessoes_pendentes
        ", [$month, $year])
        ->selectRaw("
            COALESCE((
                SELECT SUM(value) 
                FROM patient_sessions 
                WHERE patient_sessions.patient_id = patients.id 
                  AND MONTH(session_date) = ? 
                  AND YEAR(session_date) = ?
            ), 0) as total_mes_calculado
        ", [$month, $year])
        ->selectRaw("
            COALESCE((
                SELECT SUM(amount) 
                FROM payments 
                WHERE payments.patient_id = patients.id 
                  AND reference_month = ? 
                  AND reference_year = ?
            ), 0) as total_pago_mes
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
        ->orderBy('name', 'asc')
        ->get();

        $patients->each(function($patient) {
            $patient->total_mes = (float) $patient->total_mes_calculado;
            $saldoDevedor = (float) $patient->total_mes_calculado - (float) $patient->total_pago_mes;
            $patient->pendentes_no_mes = $saldoDevedor > 0 ? $saldoDevedor : 0;
        });

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
        ->selectRaw("
            COALESCE((
                SELECT SUM(value) 
                FROM patient_sessions 
                WHERE patient_sessions.patient_id = patients.id 
                  AND MONTH(session_date) = ? 
                  AND YEAR(session_date) = ?
            ), 0) as total_mes_calculado
        ", [$month, $year])
        ->selectRaw("
            COALESCE((
                SELECT SUM(amount) 
                FROM payments 
                WHERE payments.patient_id = patients.id 
                  AND reference_month = ? 
                  AND reference_year = ?
            ), 0) as total_pago_mes
        ", [$month, $year])
        ->withCount([
            'sessions as total_sessoes_mes' => function($q) use ($month, $year) {
                $q->whereMonth('session_date', $month)->whereYear('session_date', $year);
            },
        ])
        ->orderBy('name', 'asc')
        ->get();

        $patients->each(function($patient) {
            $patient->total_mes = (float) $patient->total_mes_calculado;
            $saldoDevedor = (float) $patient->total_mes_calculado - (float) $patient->total_pago_mes;
            $patient->pendentes_no_mes = $saldoDevedor > 0 ? $saldoDevedor : 0;
        });

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);

        $html = view('pdf.relatorio-mensal', compact('patients', 'month', 'year'))->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Relatorio-Lydia-Sena-'.$month.'-'.$year.'.pdf"',
        ]);
    }

    public function marcarMesComoPago(Request $request, Patient $patient)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $dataPagamento = $request->input('paid_at', now()->format('Y-m-d'));

        // 1. Calcula o montante bruto total das consultas do mês
        $totalGeradoNoMes = $patient->sessions()
            ->whereMonth('session_date', $month)
            ->whereYear('session_date', $year)
            ->sum('value');

        // 2. Calcula tudo o que já foi amortizado em recibos anteriores (parciais ou integrais)
        $totalJaPagoEmRecibos = \App\Models\Payment::where('patient_id', $patient->id)
            ->where('reference_month', $month)
            ->where('reference_year', $year)
            ->sum('amount');

        // 3. O valor restante devedor é a diferença exata
        $saldoRestanteDevedor = $totalGeradoNoMes - $totalJaPagoEmRecibos;

        if ($saldoRestanteDevedor <= 0) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Este mês já se encontra totalmente quitado.'], 422);
            }
            return redirect()->back()->with('error', 'Este mês já se encontra totalmente quitado.');
        }

        // 4. Cria o recibo com o valor real restante (R$ 500,00)
        \App\Models\Payment::create([
            'patient_id'      => $patient->id,
            'amount'          => $saldoRestanteDevedor, // CORREÇÃO: Salva os 500 e não 1000!
            'type'            => 'integral',
            'payment_date'    => $dataPagamento,
            'reference_month' => $month,
            'reference_year'  => $year,
        ]);
        
        // 5. Atualiza o status em lote das sessões na agenda
        $patient->sessions()
            ->whereMonth('session_date', $month)
            ->whereYear('session_date', $year)
            ->update([
                'status' => 'pago',
                'paid_at' => $dataPagamento
            ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pagamento integral do saldo restante registrado com sucesso!']);
        }

        return redirect()->back()->with('success', 'Pagamento registrado com sucesso!');
    }

    public function destroyPayment($id)
    {
        $payment = \App\Models\Payment::findOrFail($id);
        $patientId = $payment->patient_id;
        $month = $payment->reference_month;
        $year = $payment->reference_year;

        // 1. Remove o registro isolado de faturamento do banco de dados
        $payment->delete();

        // 2. Como um recibo sumiu, removemos preventivamente a marcação em lote de 'pago' 
        // da agenda para que as consultas voltem a acompanhar o saldo real pendente
        DB::table('patient_sessions')
            ->where('patient_id', $patientId)
            ->whereMonth('session_date', $month)
            ->whereYear('session_date', $year)
            ->update(['status' => 'pendente']);

        return response()->json([
            'success' => true,
            'message' => 'Lançamento removido do caixa com sucesso!'
        ]);
    }

    public function estornarMes(Request $request, Patient $patient)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // 1. CORREÇÃO DA COMPETÊNCIA: Remove todos os recibos atrelados ao mês de referência visível
        \App\Models\Payment::where('patient_id', $patient->id)
            ->where('reference_month', $month)
            ->where('reference_year', $year)
            ->delete();

        // 2. Retorna o status de todas as sessões daquele mês para pendente
        $patient->sessions()
            ->whereMonth('session_date', $month)
            ->whereYear('session_date', $year)
            ->update([
                'status' => 'pendente',
                'paid_at' => null
            ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Lançamentos estornados com sucesso!']);
        }

        return redirect()->back()->with('success', 'Todos os lançamentos foram revertidos.');
    }

    public function show(Request $request, Patient $patient)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // BUSCA PELA COMPETÊNCIA: Garante que os recibos parciais entrem no mês de referência correto
        $historicoPagamentos = $patient->payments()
            ->where('reference_month', $month)
            ->where('reference_year', $year)
            ->orderBy('payment_date', 'desc')
            ->get();

        $sessions = $patient->sessions()
            ->whereMonth('session_date', $month)
            ->whereYear('session_date', $year)
            ->orderBy('session_date', 'desc')
            ->get();

        $progressNotes = $patient->progressNotes()
            ->orderBy('session_date', 'desc')
            ->paginate(5, ['*'], 'notes_page'); 

        return view('patients.show', compact('patient', 'historicoPagamentos', 'month', 'year', 'sessions', 'progressNotes'));
    }

    public function create() { return view('patients.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf'  => 'required|string|max:14|unique:patients,cpf|unique:users,email',
            'email' => 'nullable|email',
        ], ['cpf.unique' => 'Este CPF já está cadastrado no sistema.']);

        $patient = Patient::create($request->all());

        \App\Models\User::create([
            'name'     => $patient->name,
            'email'    => $patient->cpf, 
            'password' => \Illuminate\Support\Facades\Hash::make('clinicalydiasena'),
            'role'     => 'patient',
        ]);

        return redirect()->route('pacientes')->with('success', 'Paciente criado com sucesso!');
    }

    public function edit(Patient $patient) { return view('patients.edit', compact('patient')); }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cpf'  => 'required|string|unique:patients,cpf,' . $id, 
        ], ['cpf.unique' => 'Este CPF já pertence a outro paciente cadastrado.']);

        $patient = Patient::findOrFail($id);
        
        if ($patient->cpf !== $request->cpf) {
            $user = User::where('email', $patient->cpf)->first();
            if ($user) $user->update(['email' => $request->cpf]);
        }

        $patient->update($request->all());
        return redirect()->route('pacientes')->with('success', 'Registro atualizado!');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('pacientes')->with('success', 'Paciente removido.');
    }

    public function markWhatsappSent(Request $request, Patient $patient)
    {
        $key = $request->year . '-' . str_pad($request->month, 2, '0', STR_PAD_LEFT);
        $logs = $patient->whatsapp_check_log ?? [];
        $logs[$key] = true;
        $patient->update(['whatsapp_check_log' => $logs]);
        return response()->json(['success' => true]);
    }
}
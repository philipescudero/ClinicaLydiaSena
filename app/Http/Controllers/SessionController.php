<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientSession;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Payment;

class SessionController extends Controller
{
    public function store(Request $request, Patient $patient)
    {
        // 0. Validação Básica
        $request->validate([
            'session_date' => 'required|date',
            'session_time' => 'required',
            'service_type' => 'required',
            'value'        => 'required|numeric',
        ]);

        // 1. Unificamos Data e Hora inicial
        $startDate = Carbon::parse($request->session_date . ' ' . $request->session_time);
        
        // CORREÇÃO: Define o fim do ano baseado estritAMENTE na data da consulta escolhida no input
        $endOfYear = $startDate->copy()->endOfYear();

        // Define o salto do loop dinamicamente (1 ou 2 semanas)
        $passoRecorrencia = ($request->recurrence_period === 'biweekly') ? 2 : 1;

        // 2. VERIFICAÇÃO DE CONFLITO INTELIGENTE
        if ($request->has('is_recurrent')) {
            $currentCheck = $startDate->copy();
            while ($currentCheck <= $endOfYear) {
                // Buscamos o conflito trazendo junto o relacionamento do paciente
                $conflito = PatientSession::with('patient')
                    ->where('session_date', $currentCheck->toDateTimeString())
                    ->first();

                if ($conflito) {
                    $nomeDonoDoHorario = $conflito->patient ? $conflito->patient->name : 'Outro paciente';
                    $msgErro = "Não é possível agendar a recorrência. Já existe um agendamento marcado para o dia {$currentCheck->format('d/m/Y')} às {$currentCheck->format('H:i')} para o paciente: {$nomeDonoDoHorario}.";

                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['success' => false, 'message' => $msgErro], 422);
                    }

                    return redirect()->back()->with('error', $msgErro)->withInput();
                }
                
                $currentCheck->addWeeks($passoRecorrencia);
            }
        } else {
            // Verificação simples para sessão avulsa
            $conflitoAvulso = PatientSession::with('patient')
                ->where('session_date', $startDate->toDateTimeString())
                ->first();

            if ($conflitoAvulso) {
                $nomeDonoDoHorario = $conflitoAvulso->patient ? $conflitoAvulso->patient->name : 'Outro paciente';
                $msgErro = "Conflito de agenda! O paciente {$nomeDonoDoHorario} já está marcado para este horário ({$startDate->format('H:i')}).";

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $msgErro], 422);
                }

                return redirect()->back()->with('error', $msgErro)->withInput();
            }
        }

        // 3. Dados base
        $data = [
            'service_type' => $request->service_type,
            'value'        => $request->value,
            'notes'        => $request->notes,
            'status'       => 'pendente',
            'performed'    => false,
        ];

        // 4. Salvamento
        if ($request->has('is_recurrent')) {
            $currentDate = $startDate->copy();
            while ($currentDate <= $endOfYear) {
                $patient->sessions()->create(array_merge($data, [
                    'session_date' => $currentDate->toDateTimeString(),
                    'is_recurrent' => true 
                ]));
                $currentDate->addWeeks($passoRecorrencia);
            }
        } else {
            $patient->sessions()->create(array_merge($data, [
                'session_date' => $startDate->toDateTimeString(),
                'is_recurrent' => false
            ]));
        }

        // --- RETORNO INTELIGENTE ASSÍNCRONO ---
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Agendamento(s) realizado(s) com sucesso!']);
        }

        return redirect()->back()->with('success', 'Agendamento(s) realizado(s) com sucesso!');
    }
    
    public function updateStatus(PatientSession $session)
    {
        if ($session->status == 'pendente') {
            $session->status = 'pago';
            $session->paid_at = now()->format('Y-m-d'); // Grava a data na hora se foi pago por ali
        } else {
            $session->status = 'pendente';
            $session->paid_at = null; // Reseta se reverter para pendente
        }
        $session->save();

        $mensagem = ($session->status == 'pago')
            ? 'Pagamento registrado com sucesso!'
            : 'Status de pagamento alterado para pendente.';

        return redirect()->back()->with('success', $mensagem);
    }

    public function update(Request $request, PatientSession $session) 
    {
        $request->validate([
            'session_date' => 'required|date',
            'session_time' => 'required',
            'service_type' => 'required',
            'value'        => 'required|numeric',
        ]);

        $dateTimeSolicitado = Carbon::parse($request->session_date . ' ' . $request->session_time)->toDateTimeString();

        $conflitoExistente = PatientSession::where('session_date', $dateTimeSolicitado)
            ->where('id', '!=', $session->id)
            ->exists();

        if ($conflitoExistente) {
            $msgErro = 'Ops! Já existe um atendimento marcado para este dia e horário.';
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msgErro], 422);
            }

            return redirect()->back()->with('error', $msgErro);
        }

        $session->update([
            'session_date' => $dateTimeSolicitado,
            'service_type' => $request->service_type,
            'value'        => $request->value,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Agendamento updated com sucesso!']);
        }

        return redirect()->back()->with('success', 'Agendamento updated com sucesso!');
    }

    public function destroy(PatientSession $session) 
{
    // Trava de segurança para impedir exclusão de pagamentos consolidados
    if ($session->status === 'pago') {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Esta sessão está paga e não pode ser excluída.'], 403);
        }
        return redirect()->back()->with('error', 'Esta consulta já está paga e não pode ser excluída.');
    }

    $session->delete();

    // SE A REQUISIÇÃO VIER DO DASHBOARD (VIA AJAX / FETCH)
    if (request()->ajax() || request()->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Sessão excluída com sucesso!'
        ]);
    }

    // SE A REQUISIÇÃO VIER DO PRONTUÁRIO (SUBMIT TRADICIONAL DE FORMULÁRIO)
    return redirect()->back()->with('success', 'Sessão excluída com sucesso!');
}

    public function destroyRecursive(PatientSession $session) 
    {
        $temPagasFuturas = PatientSession::where('patient_id', $session->patient_id)
            ->where('session_date', '>=', $session->session_date)
            ->where('is_recurrent', true)
            ->where('status', 'pago')
            ->exists();

        if ($temPagasFuturas) {
            return redirect()->back()->withErrors(['msg' => 'Não foi possível apagar a série: Existem sessões marcadas como PAGAS no período selecionado. Estorne os pagamentos antes de excluir.']);
        }

        PatientSession::where('patient_id', $session->patient_id)
            ->where('service_type', $session->service_type)
            ->where('session_date', '>=', $session->session_date)
            ->where('is_recurrent', true)
            ->where('status', 'pendente')
            ->delete();

        return redirect()->back()->with('success', 'Série de sessões futuras removida!');
    }

    public function markPerformed($id)
    {
        $session = PatientSession::findOrFail($id);
        $session->performed = !$session->performed;
        $session->save();

        $message = $session->performed ? 'Atendimento confirmed!' : 'Atendimento revertido.';

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'performed' => $session->performed,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function reversePerformed(PatientSession $session)
    {
        $session->update(['performed' => false]);
        return redirect()->back()->with('success', 'Atendimento revertido para pendente!');
    }

        public function storeFast(Request $request)
        {
            $patient = Patient::findOrFail($request->patient_id);
            return $this->store($request, $patient);
        }
        public function storePartialPayment(Request $request, Patient $patient)
        {
            $month = $request->month;
            $year = $request->year;
            $quantidadeParaPagar = (int) $request->quantidade;
            $dataPagamento = $request->input('paid_at', now()->format('Y-m-d'));

            // Busca as sessões pendentes ordenadas por data
            $sessoesPendentes = $patient->sessions()
                ->whereMonth('session_date', $month)
                ->whereYear('session_date', $year)
                ->where('status', 'pendente')
                ->orderBy('session_date', 'asc')
                ->take($quantidadeParaPagar)
                ->get();

            if ($sessoesPendentes->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Nenhuma sessão pendente encontrada.']);
            }

            // Calcula o valor total dessas sessões que estão sendo pagas
            $valorTotalParcial = $sessoesPendentes->sum('value');

            // Cria o registro no fluxo financeiro com a competência correta
            \App\Models\Payment::create([
                'patient_id'      => $patient->id,
                'amount'          => $valorTotalParcial,
                'type'            => 'parcial',
                'payment_date'    => $dataPagamento,
                'reference_month' => $month,
                'reference_year'  => $year,
            ]);

            // Atualiza o status de cada sessão para pago no banco de dados
            foreach ($sessoesPendentes as $session) {
                $session->update([
                    'status' => 'pago',
                    'paid_at' => $dataPagamento
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Pagamento de {$sessoesPendentes->count()} sessão(ões) (R$ " . number_format($valorTotalParcial, 2, ',', '.') . ") registrado com sucesso!"
            ]);
        }
        
    public function storePartialPaymentInReais(Request $request, Patient $patient)
    {
        $month = $request->month;
        $year = $request->year;
        $valorPagoOriginal = (float) $request->valor_pago;
        $dataPagamento = $request->input('paid_at', now()->format('Y-m-d'));

        if ($valorPagoOriginal <= 0) {
            return response()->json(['success' => false, 'message' => 'Valor inválido.'], 422);
        }

        // SALVA PREENCHENDO A COMPETÊNCIA DE REFERÊNCIA DE COBRANÇA
        \App\Models\Payment::create([
            'patient_id'      => $patient->id,
            'amount'          => $valorPagoOriginal,
            'type'            => 'parcial',
            'payment_date'    => $dataPagamento, // Data em que a Lydia recebeu (ex: 01/06)
            'reference_month' => $month,         // Mês que abate a dívida (ex: 05)
            'reference_year'  => $year,          // Ano que abate a dívida (ex: 2026)
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Recebimento parcial de R$ ' . number_format($valorPagoOriginal, 2, ',', '.') . ' registrado no caixa!'
        ]);
    }
    
    public function baixarMes(Request $request, Patient $patient)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $dataPagamento = $request->input('paid_at') ?? now()->format('Y-m-d');

        // 1. Calcula o valor BRUTO real gerado por todas as sessões do paciente no mês
        $totalGeradoNoMes = $patient->sessions()
            ->whereMonth('session_date', $month)
            ->whereYear('session_date', $year)
            ->sum('value');

        // 2. Calcula a soma de TODOS os recibos (parciais ou integrais) já registrados para este mês de referência
        $totalJaPagoEmRecibos = \App\Models\Payment::where('patient_id', $patient->id)
            ->where('reference_month', $month)
            ->where('reference_year', $year)
            ->sum('amount');

        // 3. O valor do recibo integral deve ser EXATAMENTE o saldo restante devedor
        $saldoRestanteDevedor = $totalGeradoNoMes - $totalJaPagoEmRecibos;

        // Se a conta já estiver zerada ou negativa, impede a criação de recibos falsos
        if ($saldoRestanteDevedor <= 0) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Este mês já se encontra totalmente quitado.'], 422);
            }
            return redirect()->back()->with('error', 'Este mês já se encontra totalmente quitado.');
        }

        // 4. Cria o recibo do pagamento integral APENAS com o valor que faltava para liquidar a fatura
        \App\Models\Payment::create([
            'patient_id'      => $patient->id,
            'amount'          => $saldoRestanteDevedor, // Agora grava os 500 restantes, e não 1000!
            'type'            => 'integral',
            'payment_date'    => $dataPagamento,
            'reference_month' => $month,
            'reference_year'  => $year,
        ]);

        // 5. Alinha o status de lote das sessões do mês para pago
        $patient->sessions()
            ->whereMonth('session_date', $month)
            ->whereYear('session_date', $year)
            ->update([
                'status' => 'pago',
                'paid_at' => $dataPagamento
            ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Pagamento integral do saldo restante registrado com sucesso!'
            ]);
        }

        return redirect()->back()->with('success', 'Pagamento integral do saldo restante registrado com sucesso!');
    }

    public function registrarPagamentoIntegral(Request $request, $patientId)
        {
            // 1. Define o período do mês atual
            $inicioMes = Carbon::now()->startOfMonth();
            $fimMes = Carbon::now()->endOfMonth();

            // 2. Busca todas as sessões "pendentes" deste paciente no mês atual
            $sessoesPendentes = PatientSession::where('patient_id', $patientId)
                ->where('status', 'pendente')
                ->whereBetween('session_date', [$inicioMes, $fimMes])
                ->get();

            // Se a Lydia clicar e não houver nada pendente, evita criar um pagamento de R$ 0,00
            if ($sessoesPendentes->isEmpty()) {
                return redirect()->back()->with('error', 'Nenhuma sessão pendente encontrada para este mês.');
            }

            // 3. Soma o valor de todas as sessões encontradas para gerar o montante ÚNICO
            $valorTotal = $sessoesPendentes->sum('value');

            // 4. Salva APENAS UM registro na nova tabela de pagamentos (Proposta B)
            Payment::create([
                'patient_id'     => $patientId,
                'amount'         => $valorTotal,
                'type'           => 'integral',
                'payment_date'   => Carbon::now()->format('Y-m-d'), // Data do clique/recebimento
            ]);

            // 5. Atualiza o status das sessões na agenda para "pago"
            // Assim a agenda fica verde/em dia, mas sem criar várias linhas no histórico de caixa
            PatientSession::whereIn('id', $sessoesPendentes->pluck('id'))
                ->update([
                    'status' => 'pago'
                ]);

            return redirect()->back()->with('success', 'Pagamento integral de R$ ' . number_format($valorTotal, 2, ',', '.') . ' registrado com sucesso!');
        }
}
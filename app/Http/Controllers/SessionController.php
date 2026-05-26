<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientSession;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        $endOfYear = Carbon::now()->endOfYear();

        // REGRA DE OURO: Define o salto do loop dinamicamente (1 ou 2 semanas)
        $passoRecorrencia = ($request->recurrence_period === 'biweekly') ? 2 : 1;

        // 2. VERIFICAÇÃO DE CONFLITO INTELIGENTE
        if ($request->has('is_recurrent')) {
            $currentCheck = $startDate->copy();
            while ($currentCheck <= $endOfYear) {
                $conflito = PatientSession::where('session_date', $currentCheck->toDateTimeString())->exists();
                if ($conflito) {
                    return redirect()->back()
                        ->withErrors(['session_time' => "Conflito na data {$currentCheck->format('d/m/Y')}: Já existe um paciente agendado às {$currentCheck->format('H:i')}."])
                        ->withInput();
                }
                // Avança 1 ou 2 semanas dependendo da escolha
                $currentCheck->addWeeks($passoRecorrencia);
            }
        } else {
            // Verificação simples para sessão avulsa
            if (PatientSession::where('session_date', $startDate->toDateTimeString())->exists()) {
                return redirect()->back()
                    ->withErrors(['session_time' => 'Ops! Lydia, você já tem um atendimento marcado para este horário.'])
                    ->withInput();
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
                // Avança 1 ou 2 semanas dependendo da escolha
                $currentDate->addWeeks($passoRecorrencia);
            }
        } else {
            $patient->sessions()->create(array_merge($data, [
                'session_date' => $startDate->toDateTimeString(),
                'is_recurrent' => false
            ]));
        }

        return redirect()->back()->with('success', 'Agendamento(s) realizado(s) com sucesso!');
    }

    // ... (Os outros métodos updateStatus, update, destroy, etc., permanecem iguais, pois já estavam corretos)
    
    public function updateStatus(PatientSession $session)
    {
        $session->status = ($session->status == 'pendente') ? 'pago' : 'pendente';
        $session->save();

        $mensagem = ($session->status == 'pago')
            ? 'Pagamento registrado com sucesso!'
            : 'Status de pagamento alterado para pendente.';

        return redirect()->back()->with('success', $mensagem);
    }

    public function update(Request $request, PatientSession $session) 
    {
        // 1. Validação dos campos que vieram do formulário de edição
        $request->validate([
            'session_date' => 'required|date',
            'session_time' => 'required',
            'service_type' => 'required',
            'value'        => 'required|numeric',
        ]);

        // 2. Unifica a data e hora recebidas para o formato padrão do banco de dados
        $dateTimeSolicitado = Carbon::parse($request->session_date . ' ' . $request->session_time)->toDateTimeString();

        // 3. VALIDADOR DE CONFLITO: Verifica se já existe OUTRA consulta nesse mesmo slot de horário
        $conflitoExistente = PatientSession::where('session_date', $dateTimeSolicitado)
            ->where('id', '!=', $session->id) // REGRA EXTRA: ignora a própria sessão para permitir atualizar valor/serviço na mesma hora
            ->exists();

        if ($conflitoExistente) {
            // Retorna com erro que é interceptado pelo nosso SweetAlert no front-end
            return redirect()->back()->with('error', 'Ops! Já existe um atendimento marcado para este dia e horário.');
        }

        // 4. Se o horário estiver livre, atualiza os dados da sessão com sucesso
        $session->update([
            'session_date' => $dateTimeSolicitado,
            'service_type' => $request->service_type,
            'value'        => $request->value,
        ]);

        return redirect()->back()->with('success', 'Agendamento atualizado com sucesso!');
    }

    public function destroy(PatientSession $session) 
    {
        // Trava de segurança: Sessão paga não pode ser excluída
        if ($session->status === 'pago') {
            return redirect()->back()->withErrors(['msg' => 'Atenção: Esta sessão já consta como PAGA e não pode ser excluída para não afetar seu faturamento.']);
        }

        $session->delete();
        return redirect()->back()->with('success', 'Sessão excluída!');
    }

    public function destroyRecursive(PatientSession $session) 
    {
        // Verifica se existem sessões PAGAS na série futura
        $temPagasFuturas = PatientSession::where('patient_id', $session->patient_id)
            ->where('session_date', '>=', $session->session_date)
            ->where('is_recurrent', true)
            ->where('status', 'pago')
            ->exists();

        if ($temPagasFuturas) {
            return redirect()->back()->withErrors(['msg' => 'Não foi possível apagar a série: Existem sessões marcadas como PAGAS no período selecionado. Estorne os pagamentos antes de excluir.']);
        }

        // Se não houver nenhuma paga, procede com a exclusão das pendentes
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
        // Usa findOrFail para garantir que se o ID for inválido, retorne erro
        $session = PatientSession::findOrFail($id);
        $session->performed = !$session->performed;
        $session->save();

        $message = $session->performed ? 'Atendimento confirmado!' : 'Atendimento revertido.';

        // O Fetch (JS) espera um retorno JSON
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
        // Busca o paciente pelo ID enviado no select
        $patient = Patient::findOrFail($request->patient_id);
        
        // Chama o método store que já criamos para aproveitar a lógica de conflitos!
        return $this->store($request, $patient);
    }
}
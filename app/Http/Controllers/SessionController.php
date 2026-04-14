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
                $currentCheck->addWeek();
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
                $currentDate->addWeek();
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
        return redirect()->back()->with('success', 'Status financeiro atualizado!');
    }

    public function update(Request $request, PatientSession $session) 
    {
        $session->update($request->all());
        return redirect()->back()->with('success', 'Sessão atualizada!');
    }

    public function destroy(PatientSession $session) 
    {
        $session->delete();
        return redirect()->back()->with('success', 'Sessão excluída!');
    }

    public function destroyRecursive(PatientSession $session) 
    {
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
        $session->performed = true;
        $session->save();
        return redirect()->back()->with('success', 'Sessão marcada como realizada!');
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
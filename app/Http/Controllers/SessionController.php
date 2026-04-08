<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Session;
use App\Models\PatientSession;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function store(Request $request, Patient $patient)
    {
        // 1. Criamos a data e hora inicial combinando os inputs
        $startDate = \Carbon\Carbon::parse($request->session_date . ' ' . $request->session_time);
        
        // 2. Definimos o limite (fim do ano atual)
        $endOfYear = \Carbon\Carbon::now()->endOfYear();

        // Dados base para salvar no banco
        $data = [
            'service_type' => $request->service_type,
            'value'        => $request->value,
            'notes'        => $request->notes,
            'status'       => 'pendente',
        ];

        if ($request->has('is_recurrent')) {
            // AQUI ESTÁ A CORREÇÃO: Definimos que o "ponteiro" começa na data inicial
            $currentDate = $startDate->copy();

            // Loop que cria sessões toda semana até o fim do ano
            while ($currentDate <= $endOfYear) {
                $patient->sessions()->create(array_merge($data, [
                    'session_date' => $currentDate->format('Y-m-d H:i:s'),
                    'is_recurrent' => true 
                ]));
                
                // Pula para a próxima semana
                $currentDate->addWeek();
            }
        } else {
            // Registro único normal
            $patient->sessions()->create(array_merge($data, [
                'session_date' => $startDate->format('Y-m-d H:i:s'),
                'is_recurrent' => false
            ]));
        }

        return redirect()->back()->with('success', 'Sessão(ões) registrada(s) com sucesso!');
    }
    public function updateStatus(PatientSession $session)
    {
        // Se está pendente vira pago, se está pago vira pendente
        $session->status = ($session->status == 'pendente') ? 'pago' : 'pendente';
        $session->save();

        return redirect()->back()->with('success', 'Status da sessão atualizado!');
    }
    public function update(Request $request, PatientSession $session) {
    $session->update($request->all());
    return redirect()->back()->with('success', 'Sessão atualizada!');
    }

    public function destroy(PatientSession $session) {
        $session->delete();
        return redirect()->back()->with('success', 'Sessão excluída!');
    }

        // E no método destroyRecursive, mude a regra para pegar só as recorrentes:
    public function destroyRecursive(PatientSession $session) {
        PatientSession::where('patient_id', $session->patient_id)
            ->where('service_type', $session->service_type)
            ->where('session_date', '>=', $session->session_date)
            ->where('is_recurrent', true) // <--- SÓ APAGA O QUE FOR RECORRENTE
            ->where('status', 'pendente')
            ->delete();

        return redirect()->back()->with('success', 'Recorrência removida sem afetar sessões avulsas!');
    }
    public function markPerformed($id) // Recebendo o ID diretamente para garantir
    {
        $session = \App\Models\PatientSession::findOrFail($id);
        
        $session->performed = true;
        $session->save(); // Forçando o save manual

        return redirect()->route('dashboard')->with('success', 'Sessão de ' . $session->patient->name . ' realizada!');
    }
        public function reversePerformed(\App\Models\PatientSession $session)
    {
        $session->update(['performed' => false]);

        return redirect()->back()->with('success', 'Status de atendimento revertido!');
    }
}
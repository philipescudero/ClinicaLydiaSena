<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\TreatmentPlan;
use Illuminate\Http\Request;

class TreatmentPlanController extends Controller
{
    // Abre a ficha
    public function index(Patient $patient)
    {
        return view('patients.treatment_plan', compact('patient'));
    }

    // Salva nova linha
    public function store(Request $request, Patient $patient)
    {
        $request->validate([
            'date' => 'required|date',
            'clinical_evolution' => 'required|string',
            'therapeutic_objectives' => 'required|string',
        ]);

        $patient->treatmentPlans()->create($request->all());

        return redirect()->back()->with('success', 'Entrada adicionada com sucesso!');
    }

    // Atualiza linha existente
    public function update(Request $request, TreatmentPlan $plan)
    {
        $request->validate([
            'clinical_evolution' => 'required|string',
            'therapeutic_objectives' => 'required|string',
        ]);

        $plan->update($request->all());

        return redirect()->back()->with('success', 'Registro updated!');
    }

    // Remove linha
    public function destroy(TreatmentPlan $plan)
    {
        $plan->delete();
        return redirect()->back()->with('success', 'Registro removido da ficha.');
    }

    // Exportação em PDF customizada para a HostGator
    public function exportPdf(Patient $patient)
    {
        // Buscamos os planos ordenados por data
        $plans = $patient->treatmentPlans()->orderBy('date', 'desc')->get();

        // --- MOTOR NATIVO DO DOMPDF (BLINDADO PARA CPANEL) ---
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', '/home1/hg477223');

        $dompdf = new \Dompdf\Dompdf($options);

        // Renderiza o HTML do plano terapêutico em memória
        $html = view('pdf.plano_terapeutico', compact('patient', 'plans'))->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();

        // Retorna o PDF em modo de visualização inline no navegador
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Plano_Terapeutico_'.$patient->name.'.pdf"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public'
        ]);
    }
}
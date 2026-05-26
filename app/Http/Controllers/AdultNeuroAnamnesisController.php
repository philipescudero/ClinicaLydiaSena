<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\AdultNeuroAnamnesis;
use Illuminate\Http\Request;

class AdultNeuroAnamnesisController extends Controller
{
    public function index(Patient $patient) 
    {
        $anamnese = AdultNeuroAnamnesis::where('patient_id', $patient->id)->first();
        return view('patients.anamnese_neuro_adulto', compact('patient', 'anamnese'));
    }

    public function store(Request $request, Patient $patient)
    {
        // Salva ou atualiza os blocos JSON limpos vindos do request
        AdultNeuroAnamnesis::updateOrCreate(
            ['patient_id' => $patient->id],
            ['sections' => $request->input('sections')]
        );

        // Redirecionamento explícito e nominal para evitar queda na rota raiz
        return redirect()->route('anamnese.neuro.adulto', $patient->id)
                         ->with('success', 'Anamnese salva com sucesso!');
    }

    public function exportPdf(Patient $patient) 
    {
        $anamnese = AdultNeuroAnamnesis::where('patient_id', $patient->id)->first();
        
        if (!$anamnese) {
            return redirect()->back()->with('error', 'Nenhum dado encontrado.');
        }

        // --- MOTOR NATIVO DO DOMPDF (BLINDADO PARA CPANEL) ---
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', '/home1/hg477223');

        $dompdf = new \Dompdf\Dompdf($options);

        // Renderiza o HTML da anamnese neuro-adulto em memória
        $html = view('pdf.anamnese_neuro_adulto', compact('patient', 'anamnese'))->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();

        // Abre em modo de visualização inline direto na aba do navegador
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Anamnese_Neuro_Adulto_'.$patient->name.'.pdf"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public'
        ]);
    }

    public function destroy($id)
    {
        $anamnese = AdultNeuroAnamnesis::findOrFail($id);
        $anamnese->delete();
        return redirect()->back()->with('success', 'Documento removido com sucesso.');
    }
}
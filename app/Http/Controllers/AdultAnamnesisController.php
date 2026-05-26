<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\AdultAnamnesis;
use Illuminate\Http\Request;

class AdultAnamnesisController extends Controller
{
    public function index(Patient $patient) 
    {
        $anamnese = AdultAnamnesis::where('patient_id', $patient->id)->first();
        return view('patients.anamnese_psico_adulto', compact('patient', 'anamnese'));
    }

    public function store(Request $request, Patient $patient)
    {
        AdultAnamnesis::updateOrCreate(
            ['patient_id' => $patient->id],
            ['sections' => $request->input('sections')]
        );

        return redirect()->back()->with('success', 'Anamnese de Adulto salva com sucesso!');
    }

    public function exportPdf(Patient $patient) 
    {
        $anamnese = AdultAnamnesis::where('patient_id', $patient->id)->first();
        
        if (!$anamnese) {
            return redirect()->back()->with('error', 'Nenhum dado encontrado.');
        }

        // --- MOTOR NATIVO DO DOMPDF (BLINDADO PARA CPANEL) ---
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', '/home1/hg477223');

        $dompdf = new \Dompdf\Dompdf($options);

        // Renderiza a view de anamnese de adulto em memória
        $html = view('pdf.anamnese_adulto', compact('patient', 'anamnese'))->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();

        // Abre em modo de visualização inline no navegador
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Anamnese_Adulto_'.$patient->name.'.pdf"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public'
        ]);
    }

    public function destroy(AdultAnamnesis $anamnesis)
    {
        $anamnesis->delete();
        return redirect()->back()->with('success', 'Documento removido.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\ChildPsicoAnamnesis;
use Illuminate\Http\Request;

class ChildPsicoAnamnesisController extends Controller
{
    public function index(Patient $patient) {
        $anamnese = ChildPsicoAnamnesis::where('patient_id', $patient->id)->first();
        return view('patients.anamnese_psico_infantil', compact('patient', 'anamnese'));
    }

    public function store(Request $request, Patient $patient) {
        ChildPsicoAnamnesis::updateOrCreate(
            ['patient_id' => $patient->id],
            ['sections' => $request->input('sections')]
        );
        return redirect()->back()->with('success', 'Anamnese Infantil salva com sucesso!');
    }

    public function exportPdf(Patient $patient) {
        $anamnese = ChildPsicoAnamnesis::where('patient_id', $patient->id)->first();
        
        if (!$anamnese) {
            return redirect()->back()->with('error', 'Nenhum dado de anamnese encontrado para este paciente.');
        }

        // --- MOTOR NATIVO DO DOMPDF (BLINDADO PARA CPANEL) ---
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', '/home1/hg477223');

        $dompdf = new \Dompdf\Dompdf($options);

        // Gera a string HTML limpa da view
        $html = view('pdf.anamnese_psico_infantil', compact('patient', 'anamnese'))->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();

        // Abre em modo de visualização na aba atual do navegador
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Anamnese_Infantil_'.$patient->name.'.pdf"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public'
        ]);
    }

    public function destroy($id)
    {
        $anamnese = ChildPsicoAnamnesis::findOrFail($id);
        $anamnese->delete();
        return redirect()->back()->with('success', 'Anamnese excluída com sucesso!');
    }
}
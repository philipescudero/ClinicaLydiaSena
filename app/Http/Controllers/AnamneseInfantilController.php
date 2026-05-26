<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\ChildNeuroAnamnesis;
use Illuminate\Http\Request;

class AnamneseInfantilController extends Controller
{
    /**
     * Exibe o formulário de anamnese para o paciente.
     */
    public function index(Patient $patient) 
    {
        $anamnese = ChildNeuroAnamnesis::where('patient_id', $patient->id)->first();
        return view('patients.anamnese_neuro_infantil', compact('patient', 'anamnese'));
    }

    /**
     * Salva ou atualiza os dados da anamnese.
     */
    public function store(Request $request, Patient $patient)
    {
        // Salvamos o conteúdo do array 'sections' que vem das abas do formulário
        ChildNeuroAnamnesis::updateOrCreate(
            ['patient_id' => $patient->id],
            ['sections' => $request->input('sections')] 
        );

        return redirect()->back()->with('success', 'Anamnese Neuropsicológica salva com sucesso!');
    }

    /**
     * Gera o PDF profissional usando a chamada direta da Facade para evitar erros de namespace.
     */
    /**
     * Gera o PDF profissional contornando as travas de pasta pública do cPanel.
     */
    public function exportPdf(Patient $patient) 
    {
        $anamnese = ChildNeuroAnamnesis::where('patient_id', $patient->id)->first();
        
        if (!$anamnese) {
            return redirect()->back()->with('error', 'Nenhum dado de anamnese encontrado para este paciente.');
        }

        // --- SOLUÇÃO ISOLADA: MOTOR NATIVO DO DOMPDF ---
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', '/home1/hg477223'); // Libera o acesso seguro aos assets da conta

        $dompdf = new \Dompdf\Dompdf($options);

        // Renderiza o HTML da folha de anamnese como string estável na memória
        $html = view('pdf.anamnese_infantil', compact('patient', 'anamnese'))->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();

        // Retorna o PDF usando o 'stream' para abrir direto na aba do navegador (visualização)
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Anamnese_'.$patient->name.'.pdf"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public'
        ]);
    }

    /**
     * Opcional: Método para excluir a anamnese se necessário.
     */
    public function destroy(ChildNeuroAnamnesis $anamnesis)
    {
        $anamnesis->delete();
        return redirect()->back()->with('success', 'Anamnese excluída com sucesso.');
    }
}
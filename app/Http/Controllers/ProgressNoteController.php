<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\ProgressNote;
use Illuminate\Http\Request;

class ProgressNoteController extends Controller
{
    // Salvar nova nota
    public function store(Request $request, Patient $patient)
    {
        // Aumentamos a segurança da validação
        $data = $request->validate([
            'session_date' => 'required|date',
            'content'      => 'required|string',
            'attachment'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Aumentado para 5MB
        ]);

        if ($request->hasFile('attachment')) {
            // Gera um nome único para o arquivo
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Salva na pasta public/notes_attachments
            $path = $file->storeAs('notes_attachments', $fileName, 'public');
            $data['attachment'] = $path;
        }

        $patient->progressNotes()->create($data);

        return redirect()->back()->with('success', 'Evolução registrada com sucesso!');
    }

    // Atualizar nota existente
    public function update(Request $request, ProgressNote $note)
    {
        $data = $request->validate([
            'session_date' => 'required|date',
            'content'      => 'required|string',
            'attachment'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('notes_attachments', $fileName, 'public');
            $data['attachment'] = $path;
        }

        $note->update($data);
        return redirect()->back()->with('success', 'Registro atualizado com sucesso!');
    }

    // Excluir nota
    public function destroy(ProgressNote $note)
    {
        $note->delete();
        return redirect()->back()->with('success', 'Registro removido do prontuário.');
    }
}
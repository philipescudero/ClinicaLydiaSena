<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClinicSetting; // Supondo que você crie uma model para os dados da clínica
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Exibe a página principal de configurações.
     */
    public function index()
    {
        // Buscamos os dados da clínica para preencher os formulários na view
        $clinicData = ClinicSetting::first() ?? new ClinicSetting();
        $admins = User::where('role', 'admin')->get();

        return view('settings.index', compact('clinicData', 'admins'));
    }

    /**
     * Altera a senha de TODOS os pacientes simultaneamente.
     */
    public function updateGlobalPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Proteção redundante (além do middleware da rota)
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        User::where('role', 'patient')->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Todas as senhas de pacientes foram atualizadas com sucesso!'
        ]);
    }

    /**
     * Atualiza os dados de contato e redes sociais da clínica.
     */
    public function updateClinicData(Request $request)
    {
        $data = $request->validate([
            'phone' => 'nullable|string',
            'instagram' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'footer_text' => 'nullable|string',
        ]);

        $setting = ClinicSetting::first() ?? new ClinicSetting();
        $setting->fill($data);
        $setting->save();

        return back()->with('success', 'Dados da clínica atualizados!');
    }

    /**
     * Adiciona um novo administrador (Secretária/Equipe).
     */
    public function storeAdmin(Request $request)
    {
        // 1. Validação (se falhar, o Laravel volta para a tela anterior com os erros)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', // Removi o "confirmed" para facilitar o formulário simples
        ]);

        // 2. Criação do Usuário
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', // Define o acesso como administrativo
        ]);

        // 3. Retorno com mensagem de sucesso
        return redirect()->route('settings.admins.index')->with('success', 'Acesso concedido com sucesso!');
    }
    // Abre a página de edição da clínica
    public function editClinic()
    {
        $clinicData = ClinicSetting::first() ?? new ClinicSetting();
        return view('settings.clinic', compact('clinicData'));
    }

    // Abre a página de gestão de equipe
    public function indexAdmins()
    {
        $admins = User::where('role', 'admin')->get();
        return view('settings.admins', compact('admins'));
    }

    public function destroyAdmin($id)
    {
        // Proteção: Não permite deletar a si mesmo
        if (Auth::id() == $id) {
            return back()->with('error', 'Você não pode remover seu próprio acesso.');
        }

        $admin = User::where('role', 'admin')->findOrFail($id);
        $admin->delete();

        return back()->with('success', 'Acesso administrativo removido com sucesso.');
    }

    public function generateBackup()
    {
        // Apenas Admins podem baixar o banco completo
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $fileName = "backup_lydia_sena_" . date('Y-m-d_H-i-s') . ".sql";

        // Comando para sistemas Windows (comum em localhost)
        // Se estiver usando MySQL/MariaDB, o comando mysqldump deve estar no PATH
        $command = "mysqldump --user={$dbUser} --password={$dbPass} --host=127.0.0.1 {$dbName} > " . storage_path("app/{$fileName}");

        exec($command);

        // Retorna o arquivo para download e deleta do servidor após enviar
        return response()->download(storage_path("app/{$fileName}"))->deleteFileAfterSend(true);
    }
    public function verifyClinicalPin(Request $request)
    {
        $request->validate(['pin' => 'required|string']);

        // Aqui você pode definir uma senha mestre no seu arquivo .env (ex: CLINICAL_PIN) 
        // ou buscar de uma tabela de configurações. Vamos usar o .env por segurança extrema.
        $pinCorreto = env('CLINICAL_PIN', '1234'); // Padrão caso não configure no .env

        if ($request->pin === $pinCorreto) {
            // Grava na sessão que o acesso clínico foi autorizado para esta navegação
            session(['clinical_access_authorized' => true]);

            return response()->json(['success' => true, 'message' => 'Acesso clínico liberado!']);
        }

        return response()->json(['success' => false, 'message' => 'PIN clínico incorreto.']);
    }

}
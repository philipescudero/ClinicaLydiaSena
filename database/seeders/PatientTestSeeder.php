<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use App\Models\PatientSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PatientTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pacientes = [
            ['name' => 'Alice da Silva Soares', 'email' => 'alice.soares@email.com', 'phone' => '+55 (11) 98765-4321', 'cpf' => '111.222.333-44', 'birth_date' => '1995-04-12', 'city_state' => 'São Paulo, SP'],
            ['name' => 'Ana Beatriz Vasconcelos', 'email' => 'ana.beatriz@email.com', 'phone' => '+55 (21) 97654-3210', 'cpf' => '222.333.444-55', 'birth_date' => '2001-08-24', 'city_state' => 'Rio de Janeiro, RJ'],
            ['name' => 'Bruno Alves Ferreira', 'email' => 'bruno.alves@email.com', 'phone' => '+55 (31) 96543-2109', 'cpf' => '333.444.555-66', 'birth_date' => '1988-11-02', 'city_state' => 'Belo Horizonte, MG'],
            ['name' => 'Larissa Meireles Cruz', 'email' => 'larissa.m@email.com', 'phone' => '+55 (19) 95432-1098', 'cpf' => '444.555.666-77', 'birth_date' => '1993-01-15', 'city_state' => 'Campinas, SP'],
            ['name' => 'Nathalia Prado Rezende', 'email' => 'nathalia.prado@email.com', 'phone' => '+55 (61) 94321-0987', 'cpf' => '555.666.777-88', 'birth_date' => '1997-07-19', 'city_state' => 'Brasília, DF'],
            ['name' => 'Kauan Silva Rodrigues', 'email' => 'kauan.silva@email.com', 'phone' => '+55 (41) 93210-9876', 'cpf' => '666.777.888-99', 'birth_date' => '2005-03-30', 'city_state' => 'Curitiba, PR'],
            ['name' => 'Philip de Sousa Escudero', 'email' => 'philip.escudero@email.com', 'phone' => '+55 (11) 92109-8765', 'cpf' => '777.888.999-00', 'birth_date' => '1990-05-14', 'city_state' => 'Santos, SP'],
            ['name' => 'Otávio Neto Mendes', 'email' => 'otavio.neto@email.com', 'phone' => '+55 (81) 91098-7654', 'cpf' => '888.999.000-11', 'birth_date' => '1982-12-25', 'city_state' => 'Recife, PE'],
            ['name' => 'Helena Reis Fontes', 'email' => 'helena.reis@email.com', 'phone' => '+55 (71) 90987-6543', 'cpf' => '999.000.111-22', 'birth_date' => '1994-09-05', 'city_state' => 'Salvador, BA'],
            ['name' => 'Eduardo Costa Miranda', 'email' => 'eduardo.costa@email.com', 'phone' => '+55 (51) 99876-1122', 'cpf' => '123.456.789-00', 'birth_date' => '1987-02-18', 'city_state' => 'Porto Alegre, RS'],
            ['name' => 'Igor Rocha Antunes', 'email' => 'igor.rocha@email.com', 'phone' => '+55 (35) 98877-3344', 'cpf' => '987.654.321-11', 'birth_date' => '1999-06-08', 'city_state' => 'Pouso Alegre, MG'],
            ['name' => 'Clara Albuquerque', 'email' => 'clara.albu@email.com', 'phone' => '+55 (11) 97766-5544', 'cpf' => '456.789.123-22', 'birth_date' => '2002-10-10', 'city_state' => 'São Paulo, SP'],
            ['name' => 'Gabriel Henrique Diniz', 'email' => 'gabriel.hd@email.com', 'phone' => '+55 (27) 96655-4433', 'cpf' => '789.123.456-33', 'birth_date' => '1991-03-22', 'city_state' => 'Vitória, ES'],
            ['name' => 'Mariana Frota Peixoto', 'email' => 'mariana.frota@email.com', 'phone' => '+55 (85) 95544-3322', 'cpf' => '321.654.987-44', 'birth_date' => '1996-12-01', 'city_state' => 'Fortaleza, CE'],
            ['name' => 'Rodrigo Souza Teixeira', 'email' => 'rodrigo.teixeira@email.com', 'phone' => '+55 (62) 94433-2211', 'cpf' => '654.987.321-55', 'birth_date' => '1985-05-17', 'city_state' => 'Goiânia, GO'],
        ];

        $horarios = ['08:00:00', '09:00:00', '10:00:00', '11:00:00', '14:00:00', '15:00:00', '16:00:00', '17:00:00'];
        
        // LINHA 38 CORRIGIDA ABAIXO COM O SINAL CORRETO (=>)
        $valores = [1 => 120.00, 2 => 250.00, 3 => 140.00]; 

        foreach ($pacientes as $key => $dados) {
            // 1. Cria ou atualiza o paciente
            $patient = Patient::updateOrCreate(
                ['cpf' => $dados['cpf']],
                [
                    'name'         => $dados['name'],
                    'email'        => $dados['email'],
                    'phone'        => $dados['phone'],
                    'birth_date'   => $dados['birth_date'],
                    'city_state'   => $dados['city_state'],
                    'observations' => 'Paciente de teste inserido automaticamente.',
                ]
            );

            // 2. Cria o usuário de acesso para o paciente
            User::updateOrCreate(
                ['email' => $patient->cpf],
                [
                    'name'     => $patient->name,
                    'password' => Hash::make('clinicalydiasena'),
                    'role'     => 'patient',
                ]
            );

            // 3. Gerar sessões fictícias em Maio de 2026 para alimentar os gráficos e listas
            $diaSemanaFixo = ($key % 5) + 1; 
            $horarioFixo = $horarios[$key % count($horarios)];
            $serviceType = ($key % 3) + 1; 

            $semanasMaio = [4, 11, 18, 25];

            foreach ($semanasMaio as $indexSemana => $diaBase) {
                $dataSessao = Carbon::create(2026, 5, $diaBase)
                    ->startOfWeek()
                    ->addDays($diaSemanaFixo - 1)
                    ->format('Y-m-d') . ' ' . $horarioFixo;

                $isPerformed = ($indexSemana < 2);
                $statusPagamento = $isPerformed ? 'pago' : 'pendente';

                $patient->sessions()->create([
                    'session_date' => $dataSessao,
                    'service_type' => $serviceType,
                    'value'        => $valores[$serviceType],
                    'status'       => $statusPagamento,
                    'performed'    => $isPerformed,
                    'is_recurrent' => true,
                ]);
            }
        }
    }
}
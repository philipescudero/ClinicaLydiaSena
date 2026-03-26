<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Cria o usuário da psicóloga para login
        User::factory()->create([
            'name' => 'Lydia Sena',
            'email' => 'contato@lydiasena.com.br',
            'password' => bcrypt('12345678'), // Senha padrão para teste
        ]);

        // Cria pacientes de teste baseados nas suas fotos
        Patient::create([
            'name' => 'Marcela Pereira',
            'email' => 'marcela@pereira.com',
            'cpf' => '123.456.789-00',
            'phone' => '(11) 98765-4321',
            'birth_date' => '1988-07-15',
            'city_state' => 'São Paulo, SP'
        ]);

        Patient::create([
            'name' => 'João Lima',
            'email' => 'joao@lima.com',
            'cpf' => '987.654.321-11',
            'phone' => '(21) 99999-8888',
            'birth_date' => '1990-05-20',
            'city_state' => 'Rio de Janeiro, RJ'
        ]);
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Garante a criação do usuário administrador da Lydia Sena
        User::updateOrCreate(
            ['email' => 'lydiasena@clinica.com'], // Procura por este e-mail
            [
                'name' => 'Lydia Sena',
                'password' => Hash::make('admin123'), // Define a senha padrão
                'role' => 'admin', // Define como admin para passar pelos controllers e gates
            ]
        );

        // 2. Chama o seeder dos 15 pacientes com as sessões de Maio que acabamos de estruturar
        $this->call([
            PatientTestSeeder::class,
        ]);
    }
}
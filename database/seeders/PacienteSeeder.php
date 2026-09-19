<?php

namespace Database\Seeders;

use App\Models\Paciente;
use Illuminate\Database\Seeder;

class PacienteSeeder extends Seeder
{
    public function run(): void
    {
        $pacientes = [
            ['nombre' => 'Juan Pérez', 'dpi' => '1234567890101', 'telefono' => '5555-1111'],
            ['nombre' => 'María García', 'dpi' => '1234567890102', 'telefono' => '5555-2222'],
            ['nombre' => 'Pedro Hernández', 'dpi' => '1234567890103', 'telefono' => '5555-3333'],
            ['nombre' => 'Sofía Morales', 'dpi' => '1234567890104', 'telefono' => '5555-4444'],
        ];

        foreach ($pacientes as $paciente) {
            Paciente::create($paciente);
        }
    }
}
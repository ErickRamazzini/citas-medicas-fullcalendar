<?php

namespace Database\Seeders;

use App\Enums\EstadoCita;
use App\Models\Cita;
use Illuminate\Database\Seeder;

class CitaSeeder extends Seeder
{
    public function run(): void
    {
        $dia = now()->addDay()->startOfDay();

        $citas = [
            [1, 1, 9, 0, 9, 30, 'Control general', EstadoCita::Pendiente],
            [2, 1, 10, 0, 10, 30, 'Dolor de cabeza', EstadoCita::Confirmada],
            [3, 2, 9, 0, 9, 45, 'Vacunación', EstadoCita::Atendida],
            [4, 3, 11, 0, 11, 30, 'Chequeo cardíaco', EstadoCita::Cancelada],
        ];

        foreach ($citas as [$pac, $doc, $hi, $mi, $hf, $mf, $motivo, $estado]) {
            Cita::create([
                'paciente_id' => $pac,
                'doctor_id' => $doc,
                'inicio' => $dia->copy()->setTime($hi, $mi),
                'fin' => $dia->copy()->setTime($hf, $mf),
                'motivo' => $motivo,
                'estado' => $estado,
            ]);
        }
    }
}
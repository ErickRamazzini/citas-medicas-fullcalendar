<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctores = [
            ['nombre' => 'Dra. Ana López', 'especialidad' => 'Medicina General'],
            ['nombre' => 'Dr. Carlos Méndez', 'especialidad' => 'Pediatría'],
            ['nombre' => 'Dra. Lucía Ramírez', 'especialidad' => 'Cardiología'],
        ];

        foreach ($doctores as $doctor) {
            Doctor::create($doctor);
        }
    }
}
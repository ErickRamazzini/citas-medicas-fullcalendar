<?php

namespace App\Repositories;

use App\Models\Cita;
use Illuminate\Database\Eloquent\Collection;

class CitaRepository
{
    public function listar(array $filtros): Collection
    {
        return Cita::with(['paciente', 'doctor'])
            ->when($filtros['doctor_id'] ?? null, fn ($q, $v) => $q->where('doctor_id', $v))
            ->when($filtros['paciente_id'] ?? null, fn ($q, $v) => $q->where('paciente_id', $v))
            ->when($filtros['desde'] ?? null, fn ($q, $v) => $q->where('fin', '>=', $v))
            ->when($filtros['hasta'] ?? null, fn ($q, $v) => $q->where('inicio', '<=', $v))
            ->orderBy('inicio')
            ->get();
    }

    public function buscar(int $id): Cita
    {
        return Cita::with(['paciente', 'doctor'])->findOrFail($id);
    }

    public function crear(array $datos): Cita
    {
        return Cita::create($datos)->load(['paciente', 'doctor']);
    }

    public function actualizar(Cita $cita, array $datos): Cita
    {
        $cita->update($datos);
        return $cita->load(['paciente', 'doctor']);
    }

    public function existeSolapamiento(int $doctorId, string $inicio, string $fin, ?int $excluirId = null): bool
    {
        return Cita::where('doctor_id', $doctorId)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->where('inicio', '<', $fin)
            ->where('fin', '>', $inicio)
            ->when($excluirId, fn ($q, $id) => $q->where('id', '!=', $id))
            ->exists();
    }
}
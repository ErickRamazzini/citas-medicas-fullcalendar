<?php

namespace App\Services;

use App\Enums\EstadoCita;
use App\Models\Cita;
use App\Repositories\CitaRepository;
use Illuminate\Database\Eloquent\Collection;

class CitaService
{
    public function __construct(private CitaRepository $repo) {}

    public function listar(array $filtros): Collection
    {
        return $this->repo->listar($filtros);
    }

    public function obtener(int $id): Cita
    {
        return $this->repo->buscar($id);
    }

    public function crear(array $datos): Cita
    {
        $datos['estado'] = EstadoCita::Pendiente;
        return $this->repo->crear($datos);
    }

    public function reprogramar(int $id, array $datos): Cita
    {
        $cita = $this->repo->buscar($id);
        return $this->repo->actualizar($cita, [
            'inicio' => $datos['inicio'],
            'fin' => $datos['fin'],
        ]);
    }

    public function cambiarEstado(int $id, EstadoCita $estado): Cita
    {
        $cita = $this->repo->buscar($id);
        return $this->repo->actualizar($cita, ['estado' => $estado]);
    }
}
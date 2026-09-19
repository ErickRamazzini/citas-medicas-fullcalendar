<?php

namespace App\Services;

use App\Enums\EstadoCita;
use App\Exceptions\ConflictoHorarioException;
use App\Exceptions\TransicionEstadoException;
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
        $this->validarDisponibilidad($datos['doctor_id'], $datos['inicio'], $datos['fin']);
        $datos['estado'] = EstadoCita::Pendiente;
        return $this->repo->crear($datos);
    }

    public function reprogramar(int $id, array $datos): Cita
    {
        $cita = $this->repo->buscar($id);

        if (! $cita->estado->esActiva()) {
            throw new TransicionEstadoException('Solo se pueden reprogramar citas pendientes o confirmadas.');
        }

        $this->validarDisponibilidad($cita->doctor_id, $datos['inicio'], $datos['fin'], $cita->id);

        return $this->repo->actualizar($cita, [
            'inicio' => $datos['inicio'],
            'fin' => $datos['fin'],
        ]);
    }

    public function cambiarEstado(int $id, EstadoCita $nuevo): Cita
    {
        $cita = $this->repo->buscar($id);

        if (! $cita->estado->puedeCambiarA($nuevo)) {
            throw new TransicionEstadoException(
                "No se puede cambiar de {$cita->estado->value} a {$nuevo->value}."
            );
        }

        return $this->repo->actualizar($cita, ['estado' => $nuevo]);
    }

    private function validarDisponibilidad(int $doctorId, string $inicio, string $fin, ?int $excluirId = null): void
    {
        if ($this->repo->existeSolapamiento($doctorId, $inicio, $fin, $excluirId)) {
            throw new ConflictoHorarioException('El doctor ya tiene una cita activa en ese horario.');
        }
    }
}
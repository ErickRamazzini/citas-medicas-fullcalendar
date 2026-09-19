<?php

namespace App\Http\Controllers\Api;

use App\Enums\EstadoCita;
use App\Http\Controllers\Controller;
use App\Http\Requests\CambiarEstadoRequest;
use App\Http\Requests\ReprogramarCitaRequest;
use App\Http\Requests\StoreCitaRequest;
use App\Http\Resources\CitaResource;
use App\Services\CitaService;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function __construct(private CitaService $service) {}

    public function index(Request $request)
    {
        $filtros = $request->only(['doctor_id', 'paciente_id', 'desde', 'hasta']);
        return CitaResource::collection($this->service->listar($filtros));
    }

    public function store(StoreCitaRequest $request)
    {
        $cita = $this->service->crear($request->validated());
        return (new CitaResource($cita))->response()->setStatusCode(201);
    }

    public function show(int $id)
    {
        return new CitaResource($this->service->obtener($id));
    }

    public function update(ReprogramarCitaRequest $request, int $id)
    {
        return new CitaResource($this->service->reprogramar($id, $request->validated()));
    }

    public function cambiarEstado(CambiarEstadoRequest $request, int $id)
    {
        $estado = EstadoCita::from($request->validated('estado'));
        return new CitaResource($this->service->cambiarEstado($id, $estado));
    }
}
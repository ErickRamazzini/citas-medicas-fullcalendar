<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CitaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'paciente' => ['id' => $this->paciente->id, 'nombre' => $this->paciente->nombre],
            'doctor' => ['id' => $this->doctor->id, 'nombre' => $this->doctor->nombre],
            'inicio' => $this->inicio->format('Y-m-d\TH:i:s'),
            'fin' => $this->fin->format('Y-m-d\TH:i:s'),
            'motivo' => $this->motivo,
            'estado' => $this->estado->value,
            'color' => $this->estado->color(),
        ];
    }
}
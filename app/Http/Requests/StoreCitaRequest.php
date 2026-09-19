<?php

namespace App\Http\Requests;

class StoreCitaRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'paciente_id' => ['required', 'integer', 'exists:pacientes,id'],
            'doctor_id' => ['required', 'integer', 'exists:doctores,id'],
            'inicio' => ['required', 'date'],
            'fin' => ['required', 'date', 'after:inicio'],
            'motivo' => ['required', 'string', 'max:255'],
        ];
    }
}
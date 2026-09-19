<?php

namespace App\Http\Requests;

class ReprogramarCitaRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'inicio' => ['required', 'date'],
            'fin' => ['required', 'date', 'after:inicio'],
        ];
    }
}
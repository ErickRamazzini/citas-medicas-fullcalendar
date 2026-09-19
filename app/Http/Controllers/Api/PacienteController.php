<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paciente;

class PacienteController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Paciente::orderBy('nombre')->get()]);
    }
}
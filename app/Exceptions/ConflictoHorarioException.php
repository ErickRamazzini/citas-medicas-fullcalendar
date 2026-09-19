<?php

namespace App\Exceptions;

use Exception;

class ConflictoHorarioException extends Exception
{
    public function render()
    {
        return response()->json(['message' => $this->getMessage()], 409);
    }
}
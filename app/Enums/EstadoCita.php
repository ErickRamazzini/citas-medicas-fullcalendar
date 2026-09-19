<?php

namespace App\Enums;

enum EstadoCita: string
{
    case Pendiente = 'pendiente';
    case Confirmada = 'confirmada';
    case Cancelada = 'cancelada';
    case Atendida = 'atendida';

    public function color(): string
    {
        return match ($this) {
            self::Pendiente => '#f59e0b',
            self::Confirmada => '#3b82f6',
            self::Cancelada => '#9ca3af',
            self::Atendida => '#10b981',
        };
    }
}
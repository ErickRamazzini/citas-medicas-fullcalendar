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

    public function puedeCambiarA(self $nuevo): bool
    {
        return match ($this) {
            self::Pendiente => in_array($nuevo, [self::Confirmada, self::Cancelada]),
            self::Confirmada => in_array($nuevo, [self::Atendida, self::Cancelada]),
            self::Cancelada, self::Atendida => false,
        };
    }

    public function esActiva(): bool
    {
        return in_array($this, [self::Pendiente, self::Confirmada]);
    }
}
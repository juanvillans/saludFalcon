<?php

namespace App\Enums;

enum StatusAppointmentsEnum: int
{
    case PENDIENTE = 1;
    case ACTIVO = 2;
    case PASADO = 3;
    case CANCELADO_POR_PACIENTE = 4;
    case CANCELADO_POR_DOCTOR = 5;

    public function label(): string
    {
        return match($this) {

            self::PENDIENTE => 1,
            self::ACTIVO => 2,
            self::PASADO => 3,
            self::CANCELADO_POR_PACIENTE => 4,
            self::CANCELADO_POR_DOCTOR => 5,
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [
                $case->value => $case->label()
            ])
            ->toArray();
    }
}

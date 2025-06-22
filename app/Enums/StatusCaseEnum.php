<?php

namespace App\Enums;

enum StatusCaseEnum: int
{
    case EGRESADO_ALTA_MEDICA = 1;
    case EGRESADO_ALTA_CONTRAMEDICA = 2;
    case TRANSFERIDO = 3;
    case INGRESADO = 4;
    case FALLECIDO = 5;
    case PERMANECE_EN = 6;
    case FUGADO = 7;

    public function label(): string
    {
        return match($this) {
            self::EGRESADO_ALTA_MEDICA => 'Egresado: Alta M.',
            self::EGRESADO_ALTA_CONTRAMEDICA => 'Egresado: Alta Co.',
            self::TRANSFERIDO => 'Transferido',
            self::INGRESADO => 'Ingresado',
            self::FALLECIDO => 'Fallecido',
            self::PERMANECE_EN => 'Permanece en',
            self::FUGADO => 'Fugado',
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

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

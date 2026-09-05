<?php

declare(strict_types=1);

namespace Escolastico\Service;

use InvalidArgumentException;

final class CalificacionService
{
    public const NOTA_MINIMA = 0.0;
    public const NOTA_MAXIMA = 10.0;

    /**
     * Calcula el promedio de tres parciales válidos y lo redondea a dos decimales.
     */
    public static function calcularPromedio(float $nota1, float $nota2, float $nota3): float
    {
        foreach ([$nota1, $nota2, $nota3] as $nota) {
            if ($nota < self::NOTA_MINIMA || $nota > self::NOTA_MAXIMA) {
                throw new InvalidArgumentException('Cada calificación debe estar entre 0 y 10.');
            }
        }

        return round(($nota1 + $nota2 + $nota3) / 3, 2, PHP_ROUND_HALF_UP);
    }
}

<?php

declare(strict_types=1);

namespace Tests;

use Escolastico\Service\CalificacionService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CalificacionServiceTest extends TestCase
{
    /** CP-U01 - Caso crítico vinculado con RF-08. */
    public function test_calcula_correctamente_el_promedio_de_tres_parciales(): void
    {
        $promedio = CalificacionService::calcularPromedio(10.0, 9.6, 9.5);

        self::assertEqualsWithDelta(9.70, $promedio, 0.001);
    }

    public function test_redondea_el_promedio_a_dos_decimales(): void
    {
        $promedio = CalificacionService::calcularPromedio(8.0, 9.0, 9.0);

        self::assertEqualsWithDelta(8.67, $promedio, 0.001);
    }

    public function test_acepta_los_limites_de_la_escala(): void
    {
        self::assertEqualsWithDelta(0.0, CalificacionService::calcularPromedio(0.0, 0.0, 0.0), 0.001);
        self::assertEqualsWithDelta(10.0, CalificacionService::calcularPromedio(10.0, 10.0, 10.0), 0.001);
    }

    public function test_rechaza_una_calificacion_superior_a_diez(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cada calificación debe estar entre 0 y 10.');

        CalificacionService::calcularPromedio(10.01, 9.0, 8.0);
    }

    public function test_rechaza_una_calificacion_negativa(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cada calificación debe estar entre 0 y 10.');

        CalificacionService::calcularPromedio(-0.01, 9.0, 8.0);
    }
}

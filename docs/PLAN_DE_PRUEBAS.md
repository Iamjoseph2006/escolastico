# Plan de pruebas - Módulo de Gestión de Calificaciones

## Objetivo y alcance

Se evaluará el módulo de Gestión de Calificaciones del Sistema Escolástico. Las pruebas cubren el registro de tres parciales, la validación de la escala de 0 a 10, el cálculo del promedio, la persistencia de la información y la consulta restringida por alumno. El módulo se considerará aprobado cuando los seis casos produzcan el resultado esperado y las pruebas automatizadas finalicen en verde en GitHub Actions.

## Ambiente

- PHP 8.2 y PHPUnit 11.5.
- MySQL/MariaDB con el esquema `ecolastico.sql` para las pruebas de integración.
- Navegador moderno para la prueba de aceptación.
- Datos independientes de producción.

## Casos de prueba

| ID | Nivel | Requerimiento | Entrada y pasos | Resultado esperado | Automatización |
|---|---|---|---|---|---|
| **CP-U01 (crítico)** | Unitaria | RF-08 | Ejecutar el cálculo con 10,00; 9,60 y 9,50. | El servicio devuelve exactamente 9,70, redondeado a dos decimales. | Sí, PHPUnit. |
| CP-U02 | Unitaria | RF-07, RF-08 | Calcular con los límites 0,00 y 10,00. | El servicio acepta ambos límites y devuelve 0,00 o 10,00 según los datos. | Sí, PHPUnit. |
| CP-U03 | Unitaria | RF-07 | Intentar calcular con 10,01 y luego con -0,01. | En ambos casos se lanza una excepción con el mensaje “Cada calificación debe estar entre 0 y 10”. | Sí, PHPUnit. |
| CP-I01 | Integración | RF-07, RF-08 | Iniciar sesión como Secretaría, registrar alumno, materia y tres parciales; consultar la tabla `notas`. | Se crea un único registro relacionado con el alumno y el promedio almacenado coincide con el cálculo del servidor. | Manual con MySQL. |
| CP-I02 | Integración | RF-13, RNF-02 | Registrar notas para dos alumnos; iniciar sesión como uno de ellos y abrir “Mis notas”. | Solo aparecen las calificaciones relacionadas con el alumno autenticado; no se muestran las del otro alumno. | Manual con aplicación y base de datos. |
| CP-A01 | Aceptación | RF-07, RF-08, RF-13 | Secretaría registra 8,00; 9,00 y 10,00 para un alumno; posteriormente el alumno inicia sesión y consulta sus notas. | El alumno observa la materia, los tres parciales y el promedio 9,00 sin poder editar ni eliminar el registro. | Manual, flujo de usuario. |

## Caso más crítico

El caso **CP-U01** es el más crítico porque el promedio es el resultado académico principal del módulo. Un cálculo incorrecto afectaría la información consultada por el alumno y volvería inválido el cumplimiento de RF-08. Por ello se automatizó con PHPUnit y se ejecuta en cada `push` y `pull_request` mediante GitHub Actions.

## Criterio de cierre

El plan se aprueba cuando no existen fallos en los cinco tests automatizados, los casos de integración confirman la persistencia y el aislamiento de datos, y el flujo de aceptación muestra el promedio correcto al alumno.

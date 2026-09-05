# Contenido para consolidar la Unidad 3

## 9. Selección tecnológica

Se implementó el módulo **Gestión de calificaciones**, correspondiente a RF-07, RF-08 y RF-13 del SRS. El módulo registra tres parciales, calcula el promedio y permite al alumno consultar sus propios resultados. La matriz comparó PHP 8.2 con MVC/PDO, PHP con Laravel y JavaScript con Express. Con pesos de 40 % para curva de aprendizaje, 30 % para comunidad y soporte y 30 % para idoneidad, los resultados fueron 4,7; 4,2 y 3,9, respectivamente. Se eligió PHP 8.2 con MVC/PDO y MySQL/MariaDB porque aprovecha el código existente, reduce la curva de aprendizaje y responde al dominio de una aplicación web académica.

## 10. Control de versiones

Repositorio: https://github.com/Iamjoseph2006/escolastico

Se utilizó GitHub Flow. La rama `main` conserva el código estable y los cambios se desarrollan en ramas `feature/...`. El repositorio conserva el pull request 1 como evidencia previa del flujo. Para la automatización se creó `feature/pruebas-ci-notas`; después de ejecutar las verificaciones se debe abrir un pull request y fusionarlo únicamente con el pipeline en verde.

## 11. Plan y casos de prueba

El plan abarca el cálculo, la validación de la escala, la persistencia y el control de acceso. Contiene tres casos unitarios, dos de integración y uno de aceptación. CP-U01 se seleccionó como crítico porque valida el promedio exigido por RF-08. La tabla completa se encuentra en `PLAN_DE_PRUEBAS.md`.

## 12. Automatización de pruebas

Se automatizó CP-U01 con PHPUnit 11.5. La clase `CalificacionService` concentra la regla de promedio y valida que cada parcial esté entre 0 y 10. La prueba envía 10,00; 9,60 y 9,50 y verifica un resultado de 9,70. También se automatizaron el redondeo, los valores límite y el rechazo de calificaciones inválidas.

El workflow `.github/workflows/ci.yml` se activa en cada `push` y `pull_request`. Configura PHP 8.2, instala las dependencias con Composer y ejecuta `composer test`. La captura definitiva debe mostrar el job “PHPUnit en PHP 8.2” con check verde y el resultado de los cinco tests.

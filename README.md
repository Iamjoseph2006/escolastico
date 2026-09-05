# Sistema Escolástico

Aplicación web para una institución educativa pequeña. Centraliza el acceso por roles y la gestión de alumnos, calificaciones, asistencia y documentos académicos.

## Módulo implementado en la Unidad 3

**Gestión de calificaciones:** permite a Secretaría registrar tres calificaciones parciales por materia, calcular automáticamente el promedio y permitir que cada alumno consulte únicamente sus resultados.

El módulo responde a los siguientes requerimientos del SRS:

- **RF-07:** registrar tres calificaciones parciales por materia.
- **RF-08:** calcular automáticamente el promedio de calificaciones.
- **RF-13:** permitir al alumno consultar sus calificaciones y promedio.

## Stack tecnológico

- PHP 8.2.
- Arquitectura MVC propia y acceso a datos mediante PDO.
- MySQL/MariaDB.
- Bootstrap 5 para la interfaz.
- PHPUnit 11.5 para pruebas automatizadas.
- GitHub Actions para integración continua.

La matriz ponderada que sustenta esta selección se encuentra en [`docs/matriz_decision_tecnologica_U3.xlsx`](docs/matriz_decision_tecnologica_U3.xlsx).

## Estructura principal

```text
escolastico/
├── .github/workflows/ci.yml
├── config/
├── controller/
├── docs/
├── model/
├── src/Service/CalificacionService.php
├── tests/CalificacionServiceTest.php
├── view/
├── composer.json
├── phpunit.xml
└── ecolastico.sql
```

## Instalación local

Requisitos: PHP 8.2, Composer 2 y MySQL o MariaDB.

1. Clonar el repositorio.
2. Ejecutar `composer install`.
3. Importar `ecolastico.sql` en MySQL/MariaDB.
4. Configurar las variables `DB_HOST`, `DB_NAME`, `DB_USER` y `DB_PASSWORD` si los valores locales son diferentes.
5. Servir la carpeta como `htdocs/escolastico` en Apache/XAMPP y abrir `http://localhost/escolastico/`.

## Pruebas automatizadas

```bash
composer test
```

La prueba crítica comprueba que tres parciales válidos produzcan el promedio correcto. También se verifican el redondeo y el rechazo de valores fuera de la escala de 0 a 10.

## Flujo de ramas

Se aplica un flujo basado en GitHub Flow:

1. `main` conserva el código estable.
2. Cada cambio se realiza en una rama `feature/...`.
3. Se registra un commit con un mensaje descriptivo.
4. Se abre un pull request y se revisa el resultado de GitHub Actions.
5. Solo se fusiona cuando el pipeline está en verde.

Para la automatización de la Unidad 3 se utiliza la rama `feature/pruebas-ci-notas`.

## Integrante

- José Jhair Hernández Tseremp - Grupo 02 (G02).

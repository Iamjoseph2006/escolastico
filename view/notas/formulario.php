<?php
require_once '../../config/Auth.php';
require_secretaria();

// Se importa el modelo Nota.
// Este modelo permite listar, registrar, editar y eliminar notas.
require_once '../../model/Notas.php';

// Se importa el modelo Alumno.
// Este modelo permite obtener alumnos para mostrarlos en el select.
require_once '../../model/Alumno.php';


// Se crea un objeto del modelo Nota.
$nota = new Notas();

// Se crea un objeto del modelo Alumno.
$alumno = new Alumno();


// Arreglo con valores vacíos.
// Sirve para que el formulario funcione al registrar y al editar.
$data = [
    'id_nota' => '',
    'materia' => '',
    'nota1' => '',
    'nota2' => '',
    'nota3' => '',
    'npromedio' => '',
    'id_alumno' => ''
];


// Si existe un ID en la URL, significa que se está editando una nota.
// Ejemplo: formulario.php?id=2
if (isset($_GET['id'])) {

    // Se obtiene la nota por ID.
    // Si no encuentra datos, mantiene el arreglo vacío.
    $data = $nota->obtenerPorId($_GET['id']) ?? $data;
}


// Se obtienen todas las notas registradas para mostrarlas en la tabla.
$notas = $nota->obtenerTodos();


// Se obtienen todos los alumnos registrados.
// Esto sirve para llenar el select de alumnos.
$alumnos = $alumno->obtenerTodo();

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Notas</title>

  <!-- Bootstrap para el diseño visual -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Íconos de Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --bg: #f4f7fb;
      --surface: rgba(255, 255, 255, 0.92);
      --text: #172033;
      --muted: #667085;
      --line: #e6eaf0;
      --primary: #2563eb;
      --primary-dark: #1d4ed8;
      --primary-soft: #eaf1ff;
      --danger: #dc2626;
      --danger-soft: #fff1f2;
      --warning: #d97706;
      --warning-soft: #fffbeb;
      --shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
      --radius-lg: 28px;
      --radius-md: 18px;
      --radius-sm: 14px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      min-height: 100vh;
      margin: 0;
      background:
        radial-gradient(circle at top left, rgba(37, 99, 235, 0.13), transparent 32rem),
        radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.11), transparent 30rem),
        var(--bg);
      color: var(--text);
      font-family: "Segoe UI", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
      padding: 2rem 1rem 3rem;
    }

    .main-container {
      width: min(1160px, 100%);
      margin: 0 auto;
    }

    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 1.25rem;
      padding: 0.7rem 1rem;
      border: 1px solid var(--line);
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.76);
      color: var(--text);
      font-size: 0.95rem;
      font-weight: 700;
      text-decoration: none;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
      backdrop-filter: blur(16px);
      transition: color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }

    .back-link:hover {
      border-color: rgba(37, 99, 235, 0.28);
      color: var(--primary);
      transform: translateY(-1px);
      box-shadow: 0 14px 32px rgba(37, 99, 235, 0.12);
    }

    .card-custom {
      position: relative;
      overflow: hidden;
      margin-bottom: 1.5rem;
      padding: 1.5rem;
      border: 1px solid rgba(230, 234, 240, 0.95);
      border-radius: var(--radius-lg);
      background: var(--surface);
      box-shadow: var(--shadow);
      backdrop-filter: blur(18px);
    }

    .card-custom::before {
      content: "";
      position: absolute;
      inset: 0 0 auto;
      height: 5px;
      background: linear-gradient(90deg, var(--primary), #0ea5e9);
    }

    .title {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
      margin: 0 0 1.6rem;
      color: var(--text);
      font-size: 1.35rem;
      font-weight: 800;
      letter-spacing: -0.03em;
      text-align: center;
    }

    .title i {
      display: inline-grid;
      place-items: center;
      width: 44px;
      height: 44px;
      border-radius: 15px;
      background: var(--primary-soft);
      color: var(--primary);
      font-size: 1.2rem;
    }

    .form-group {
      position: relative;
      margin-bottom: 1.2rem;
    }

    .form-group i {
      position: absolute;
      top: 50%;
      left: 1rem;
      z-index: 2;
      color: var(--muted);
      transform: translateY(-50%);
      transition: color 0.2s ease;
    }

    .form-group:focus-within i {
      color: var(--primary);
    }

    .form-control,
    .form-select {
      min-height: 52px;
      border: 1px solid var(--line);
      border-radius: var(--radius-sm);
      background-color: rgba(255, 255, 255, 0.94);
      color: var(--text);
      font-size: 0.95rem;
      padding-left: 2.75rem;
      box-shadow: none;
      transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    .form-control::placeholder {
      color: #98a2b3;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: rgba(37, 99, 235, 0.55);
      background-color: #ffffff;
      box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .form-control[readonly] {
      background-color: #f8fafc;
      color: #64748b;
      cursor: not-allowed;
    }

    .promedio-box {
      background-color: #f8fafc;
      color: var(--text);
      font-weight: 800;
    }

    .d-flex.gap-2.mt-2 {
      display: grid !important;
      grid-template-columns: minmax(0, 1fr) auto;
      gap: 0.85rem !important;
      margin-top: 0.35rem !important;
    }

    .btn-save,
    .btn-clear {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.55rem;
      min-height: 52px;
      border-radius: var(--radius-sm);
      font-weight: 800;
      text-decoration: none;
      transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
    }

    .btn-save {
      width: 100%;
      border: none;
      background: linear-gradient(135deg, var(--primary), #0ea5e9);
      color: #ffffff;
      padding: 0 1.4rem;
      box-shadow: 0 14px 30px rgba(37, 99, 235, 0.22);
    }

    .btn-save:hover {
      background: linear-gradient(135deg, var(--primary-dark), #0284c7);
      transform: translateY(-1px);
      box-shadow: 0 18px 36px rgba(37, 99, 235, 0.28);
    }

    .btn-clear {
      width: auto;
      min-width: 150px;
      border: 1px solid var(--line) !important;
      background: #ffffff !important;
      color: #475467 !important;
      padding: 0 1.25rem;
    }

    .btn-clear:hover {
      border-color: #cbd5e1 !important;
      background: #f8fafc !important;
      color: var(--text) !important;
      transform: translateY(-1px);
    }

    .table-responsive {
      padding: 0.15rem;
    }

    .table {
      margin: 0;
      border-collapse: separate;
      border-spacing: 0 0.55rem;
    }

    .table thead th {
      border: 0;
      background: transparent;
      color: var(--muted);
      font-size: 0.76rem;
      font-weight: 800;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      padding: 0.75rem 1rem;
      white-space: nowrap;
    }

    .table tbody tr {
      border-radius: var(--radius-md);
      box-shadow: 0 10px 26px rgba(15, 23, 42, 0.04);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .table-hover tbody tr:hover {
      transform: translateY(-1px);
      box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
    }

    .table tbody td {
      border-top: 1px solid var(--line);
      border-bottom: 1px solid var(--line);
      background: #ffffff;
      color: #344054;
      padding: 1rem;
      vertical-align: middle;
    }

    .table tbody td:first-child {
      border-left: 1px solid var(--line);
      border-top-left-radius: var(--radius-md);
      border-bottom-left-radius: var(--radius-md);
      color: var(--primary);
      font-weight: 800;
    }

    .table tbody td:last-child {
      border-right: 1px solid var(--line);
      border-top-right-radius: var(--radius-md);
      border-bottom-right-radius: var(--radius-md);
    }

    .promedio-tabla {
      font-weight: 800;
      color: #344054;
    }

    .action-btn {
      display: inline-grid;
      place-items: center;
      width: 40px;
      height: 40px;
      margin: 0 0.12rem;
      border: 1px solid transparent;
      border-radius: 12px;
      background: transparent;
      font-size: 1.05rem;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .edit-btn {
      background: var(--warning-soft);
      color: var(--warning);
    }

    .delete-btn {
      background: var(--danger-soft);
      color: var(--danger);
    }

    .action-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08);
    }

    @media (max-width: 768px) {
      body {
        padding: 1rem 0.75rem 2rem;
      }

      .card-custom {
        padding: 1.15rem;
        border-radius: 22px;
      }

      .title {
        align-items: flex-start;
        justify-content: flex-start;
        text-align: left;
      }

      .d-flex.gap-2.mt-2 {
        grid-template-columns: 1fr;
      }

      .btn-clear {
        width: 100%;
      }
    }
  </style>
</head>

<body>

<div class="main-container">

  <!-- Enlace para volver al dashboard -->
  <a href="../dashboard/dashboard.php" class="back-link">
    <i class="bi bi-arrow-left-circle"></i> Volver al dashboard
  </a>

  <!-- Tarjeta del formulario -->
  <div class="card-custom">

    <!-- Título dinámico: cambia entre Registro y Editar -->
    <h4 class="title">
      <i class="bi bi-journal-check"></i>
      <?= isset($_GET['id']) ? 'Editar Nota' : 'Registro de Nota' ?>
    </h4>

    <!-- Formulario que enviará los datos al controlador de notas -->
    <form action="../../controller/NotasController.php" method="POST" onsubmit="calcularPromedio()">

      <!-- Si se está editando, se envía el id_nota oculto -->
      <?php if (isset($_GET['id'])): ?>
        <input type="hidden" name="id_nota" value="<?= htmlspecialchars($data['id_nota']) ?>">
        <input type="hidden" name="accion" value="editar">
      <?php endif; ?>

      <div class="row">

        <!-- SELECT DE ALUMNO -->
        <div class="col-md-6">
          <div class="form-group">
            <i class="bi bi-person-fill"></i>
            <select name="id_alumno" class="form-select" required>
              <option value="">Seleccione un alumno</option>

              <!-- Se recorren todos los alumnos para llenar el select -->
              <?php foreach ($alumnos as $a): ?>
                <option
                  value="<?= htmlspecialchars($a['id_alumno']) ?>"
                  <?= $data['id_alumno'] == $a['id_alumno'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($a['nombres'] . ' ' . $a['apellidos']) ?>
                </option>
              <?php endforeach; ?>

            </select>
          </div>
        </div>

        <!-- CAMPO MATERIA -->
        <div class="col-md-6">
          <div class="form-group">
            <i class="bi bi-book-fill"></i>
            <input
              type="text"
              name="materia"
              class="form-control"
              placeholder="Materia"
              value="<?= htmlspecialchars($data['materia']) ?>"
              required>
          </div>
        </div>

        <!-- CAMPO NOTA 1 -->
        <div class="col-md-3">
          <div class="form-group">
            <i class="bi bi-clipboard-data"></i>
            <input
              type="number"
              step="0.01"
              min="0"
              max="100"
              name="nota1"
              id="nota1"
              class="form-control"
              placeholder="Nota 1"
              value="<?= htmlspecialchars($data['nota1']) ?>"
              oninput="calcularPromedio()"
              required>
          </div>
        </div>

        <!-- CAMPO NOTA 2 -->
        <div class="col-md-3">
          <div class="form-group">
            <i class="bi bi-clipboard-data"></i>
            <input
              type="number"
              step="0.01"
              min="0"
              max="100"
              name="nota2"
              id="nota2"
              class="form-control"
              placeholder="Nota 2"
              value="<?= htmlspecialchars($data['nota2']) ?>"
              oninput="calcularPromedio()"
              required>
          </div>
        </div>

        <!-- CAMPO NOTA 3 -->
        <div class="col-md-3">
          <div class="form-group">
            <i class="bi bi-clipboard-data"></i>
            <input
              type="number"
              step="0.01"
              min="0"
              max="100"
              name="nota3"
              id="nota3"
              class="form-control"
              placeholder="Nota 3"
              value="<?= htmlspecialchars($data['nota3']) ?>"
              oninput="calcularPromedio()"
              required>
          </div>
        </div>

        <!-- CAMPO PROMEDIO -->
        <div class="col-md-3">
          <div class="form-group">
            <i class="bi bi-calculator-fill"></i>
            <input
              type="text"
              name="npromedio"
              id="npromedio"
              class="form-control promedio-box"
              placeholder="Promedio"
              value="<?= htmlspecialchars($data['npromedio']) ?>"
              readonly>
          </div>
        </div>

      </div>

      <div class="d-flex gap-2 mt-2">

        <!-- Botón guardar o actualizar -->
        <button type="submit" class="btn-save">
          <i class="bi bi-save me-2"></i>
          <?= isset($_GET['id']) ? 'Actualizar Nota' : 'Guardar Nota' ?>
        </button>

        <!-- Botón limpiar -->
        <a href="formulario.php" class="btn btn-outline-secondary btn-clear">
          <i class="bi bi-x-circle me-2"></i>
          Limpiar
        </a>

      </div>

    </form>
  </div>

  <!-- Tarjeta de la tabla -->
  <div class="card-custom">

    <h4 class="title">
      <i class="bi bi-table"></i>
      Lista de Notas
    </h4>

    <div class="table-responsive">

      <table class="table table-hover align-middle">

        <thead>
          <tr>
            <th>ID Nota</th>
            <th>Alumno</th>
            <th>Materia</th>
            <th>Nota 1</th>
            <th>Nota 2</th>
            <th>Nota 3</th>
            <th>Promedio</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>

        <tbody>

          <!-- Se recorren todas las notas registradas -->
          <?php foreach ($notas as $n): ?>

            <tr>
              <td><?= htmlspecialchars($n['id_nota']) ?></td>

              <td>
                <?= htmlspecialchars($n['nombres'] . ' ' . $n['apellidos']) ?>
              </td>

              <td><?= htmlspecialchars($n['materia']) ?></td>

              <td>
                <?= htmlspecialchars($n['nota1']) ?>
              </td>

              <td>
                <?= htmlspecialchars($n['nota2']) ?>
              </td>

              <td>
                <?= htmlspecialchars($n['nota3']) ?>
              </td>

              <td>
                <span class="promedio-tabla">
                  <?= number_format($n['npromedio'], 2) ?>
                </span>
              </td>

              <td class="text-center">

                <!-- Botón editar -->
                <a
                  href="formulario.php?id=<?= htmlspecialchars($n['id_nota']) ?>"
                  class="action-btn edit-btn"
                  title="Editar">
                  <i class="bi bi-pencil-square"></i>
                </a>

                <!-- Botón eliminar -->
                <a
                  href="../../controller/NotasController.php?eliminar=<?= htmlspecialchars($n['id_nota']) ?>"
                  class="action-btn delete-btn"
                  title="Eliminar"
                  onclick="return confirm('¿Está seguro de eliminar esta nota?');">
                  <i class="bi bi-trash-fill"></i>
                </a>
                <a href='../../controller/ReporteNotasController.php?accion=reporte&id=<?= $n['id_nota'] ?>' target='_blank' class='btn btn-sm btn-primary'>
                                 <i class="bi bi-file-earmark-pdf"></i> PDF
                                    </a>
              </td>
            </tr>

          <?php endforeach; ?>

        </tbody>

      </table>

    </div>
  </div>

</div>

<script>
  // Función para calcular el promedio de las tres notas.
  // Esta operación se realiza en JavaScript antes de enviar a PHP.
  function calcularPromedio() {

    // Se obtiene la nota 1 desde el input.
    // parseFloat convierte el valor a número decimal.
    // Si está vacío, se toma como 0.
    let nota1 = parseFloat(document.getElementById('nota1').value) || 0;

    // Se obtiene la nota 2.
    let nota2 = parseFloat(document.getElementById('nota2').value) || 0;

    // Se obtiene la nota 3.
    let nota3 = parseFloat(document.getElementById('nota3').value) || 0;

    // Se calcula el promedio.
    let npromedio = (nota1 + nota2 + nota3) / 3;

    // Se coloca el resultado en el input promedio.
    document.getElementById('npromedio').value = npromedio.toFixed(2);
  }

  // Se ejecuta la función al cargar la página.
  // Esto sirve cuando se abre el formulario en modo edición.
  calcularPromedio();
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
